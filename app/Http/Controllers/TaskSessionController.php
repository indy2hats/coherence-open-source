<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\SessionType;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskSession;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Services\BusinessDays;
use Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

date_default_timezone_set('Asia/Kolkata');

class TaskSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'total' => [
                'required',
                'regex:/^(?!.*:\d{3,})(\d+:)?\d*(\.\d{1,2})?$/',
                function ($attribute, $value, $fail) {
                    [$hours, $minutes] = explode(':', str_replace('.', ':', $value).':00');
                    if ($hours >= 24) {
                        $fail('The '.$attribute.' field must be less than 24 hours.');
                    }
                },
            ],
            'comments' => 'required',
        ]);

        $date = Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
        $taskExists = TaskSession::select('id')
            ->where('task_id', request('task_id'))
            ->where('user_id', Auth::user()->id)
            ->where('created_at', 'like', '%'.$date.'%')
            ->exists();

        if ($taskExists) {
            return response()->json(['success' => false, 'message' => 'TaskSession already created for the date']);
        } else {
            $total = request('total');
            if (strpos($total, ':') !== false) {
                [$hours, $minutes] = explode(':', $total);
                $min = ($hours * 60) + $minutes;
            } else {
                $min = floor($total * 60);
            }

            if ($min >= 1440) {
                return response()->json(['success' => false, 'message' => 'Invalid Time Entry']);
            }

            $taskData = [
                'task_id' => request('task_id'),
                'user_id' => Auth::user()->id,
                'current_status' => 'over',
                'created_at' => Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d'),
                'total' => $min,
                'billed_today' => 0,
                'comments' => request('comments'),
                'session_type' => request('session_type')
            ];

            TaskSession::create($taskData);

            Task::find(request('task_id'))->update(['status' => request('status')]);

            $this->updateTaskCompletion(request('task_id'));

            return response()->json(['success' => true, 'message' => 'TaskSession created successfully', 'time' => $min]);
        }
    }

    public function edit($id)
    {
        $session = TaskSession::where('id', $id)->first();

        return view('employeetasks.edit', compact('session'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'task_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'total' => [
                'required',
                'regex:/^(?!.*:\d{3,})(\d+:)?\d*(\.\d{1,2})?$/',
                function ($attribute, $value, $fail) {
                    [$hours, $minutes] = explode(':', str_replace('.', ':', $value).':00');
                    if ($hours >= 24) {
                        $fail('The '.$attribute.' field must be less than 24 hours.');
                    }
                },
            ],
            'comments' => 'required',
        ]);

        $createdAt = Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
        Session::has('taskRunning') ? ((Session::get('taskRunning') == (int) request('task_id')) ? Session::forget('taskRunning') : '') : '';
        Session::has('taskPaused') ? ((Session::get('taskPaused') == (int) request('task_id')) ? Session::forget('taskPaused') : '') : '';
        if (request('total') == '') {
            return response()->json(['success' => false, 'message' => 'Invalid Time Entry', 'time' => TaskSession::select('total')->where('id', $id)->first()]);
        } else {
            $total = request('total');
            if (strpos($total, ':') !== false) {
                [$hours, $minutes] = explode(':', $total);
                $min = ($hours * 60) + $minutes;
            } else {
                $min = floor($total * 60);
            }

            $currentSession = TaskSession::where('id', $id)->first();

            $validateExist = TaskSession::where('created_at', 'like', '%'.$createdAt.'%')->where('user_id', Auth::user()->id)->where('task_id', request('task_id'))->first();

            $this->removeTaskFromPauseList((int) request('task_id'));
            if ($validateExist != null) {
                if ($currentSession->id == $validateExist->id) {
                    $taskData = [
                        'total' => $min,
                        'billed_today' => 0,
                        'comments' => request('comments'),
                        'current_status' => 'over',
                        'session_type' => request('session_type')
                    ];

                    TaskSession::find($id)->update($taskData);

                    $this->updateTaskCompletion(request('task_id'));
                    $this->checkTaskIsRunning();

                    return response()->json(['success' => true, 'message' => 'TaskSession details updated successfully', 'time' => $min]);
                } else {
                    if ($validateExist) {
                        TaskSession::find($id)->delete();
                        TaskSession::find($validateExist->id)->delete();
                    }

                    $taskData = [
                        'task_id' => request('task_id'),
                        'user_id' => Auth::user()->id,
                        'current_status' => 'over',
                        'created_at' => $createdAt,
                        'total' => $min,
                        'billed_today' => 0,
                        'comments' => request('comments'),
                        'session_type' => request('session_type')
                    ];

                    TaskSession::create($taskData);

                    $this->updateTaskCompletion(request('task_id'));

                    return response()->json(['message' => 'TaskSession created successfully updated']);
                }
            } else {
                TaskSession::find($id)->delete();

                $taskData = [
                    'task_id' => request('task_id'),
                    'user_id' => Auth::user()->id,
                    'current_status' => 'over',
                    'created_at' => $createdAt,
                    'total' => $min,
                    'billed_today' => 0,
                    'comments' => request('comments'),
                    'session_type' => request('session_type')
                ];

                TaskSession::create($taskData);

                $this->updateTaskCompletion(request('task_id'));

                return response()->json(['message' => 'TaskSession created successfully since the date has changed']);
            }
        }
    }

    /** Delete task session*/
    public function destroy($id)
    {
        $task = TaskSession::where('id', $id)->first();

        TaskSession::find($id)->delete();
        $sessionDate = Carbon::parse($task->created_at)->format('d-m-Y');
        $today = Carbon::now()->format('d-m-Y');

        if ($sessionDate == $today) {
            Session::forget('taskPaused');
            $this->removeTaskFromPauseList($task->task_id);
            Session::forget('taskRunning');
        }
        $this->updateTaskCompletion($task->task_id);

        return response()->json(['message' => 'Session Deleted successfully']);
    }

    public function addTaskSession()
    {
        $this->addEasyAccess(request('task-id'));

        $taskSession = TaskSession::select('id')->where('task_id', request('task-id'))->where('user_id', Auth::user()->id)->where('created_at', 'like', '%'.date('Y-m-d').'%')->first();
        Session::forget('taskPaused');
        $taskList = Session::get('pausedTask') ?? [];
        if (($key = array_search((int) request('task-id'), $taskList)) !== false) {
            unset($taskList[$key]);
        }
        Session::put('pausedTask', $taskList);
        Session::put('taskRunning', (int) request('task-id'));
        if ($taskSession != null) {
            $taskData = [
                'start_time' => date('Y-m-d H:i:s'),
                'current_status' => request('status_task') ?? 'started',
            ];

            TaskSession::find($taskSession->id)->update($taskData);

            $task = Task::where('id', request('task-id'))->first();

            Task::find(request('task-id'))->update(['status' => request('status')]);

            return response()->json(['success' => true, 'status' => request('status')]);
        } else {
            $taskData = [
                'task_id' => request('task-id'),
                'user_id' => Auth::user()->id,
                'start_time' => date('Y-m-d H:i:s'),
                'current_status' => 'started',
                'total' => 0,
            ];

            TaskSession::create($taskData);

            $task = Task::where('id', request('task-id'))->first();

            Task::find(request('task-id'))->update(['status' => request('status')]);

            return response()->json(['success' => true, 'status' => request('status')]);
        }
    }

    public function stopSession(Request $request)
    {
        $request->validate([
            'comment' => 'required'
        ], ['comment.required' => 'Please enter your comments']);
        $taskId = (int) request('task-id');
        $taskSession = TaskSession::select('id', 'start_time', 'total', 'comments', 'end_time', 'current_status')
            ->where('task_id', $taskId)
            ->where('user_id', Auth::user()->id)
            ->where('created_at', 'like', '%'.date('Y-m-d').'%')
            ->whereIn('current_status', ['started', 'pause', 'resume'])
            ->first();
        $endTime = new DateTime(date('Y-m-d H:i:s'));
        Session::has('taskRunning') ? ((Session::get('taskRunning') == $taskId) ? Session::forget('taskRunning') : '') : '';
        Session::has('taskPaused') ? ((Session::get('taskPaused') == $taskId) ? Session::forget('taskPaused') : '') : '';
        $taskList = Session::get('pausedTask');
        if (($key = array_search($taskId, $taskList)) !== false) {
            $endTime = new DateTime($taskSession->end_time ?? 'now');
            unset($taskList[$key]);
        }
        Session::put('pausedTask', $taskList);

        $start_time = new DateTime($taskSession->start_time);
        $diff = $start_time->diff($endTime);
        $min = ($diff->h * 60) + $diff->i;
        $defaultPauseMessage = config('general.task.task_session.default_task_pause_message');
        $comments = (trim($taskSession->comments) == $defaultPauseMessage) ? request('comment') : $taskSession->comments."\n".request('comment');
        $total = ($taskSession->current_status == config('general.task.session.pause')) ? $taskSession->total : $taskSession->total + $min;

        $data = [
            'current_status' => 'over',
            'total' => $total,
            'end_time' => $endTime,
            'billed_today' => 0,
            'session_type' => request('session_type'),
            'comments' => $comments
        ];

        if ($total > 0) {
            TaskSession::find($taskSession->id)->update($data);
        } else {
            TaskSession::find($taskSession->id)->delete();
        }

        $this->updateTaskCompletion(request('task-id'));
        $this->removeEasyAccess();

        return response()->json(['message' => 'Session ended']);
    }

    public function checkSession()
    {
        $taskSession = TaskSession::select('id', 'start_time', 'task_id', 'end_time', 'current_status', 'total')
            ->has('task.project')
            ->where('user_id', Auth::user()->id)
            ->whereIn('current_status', ['started', 'resume'])
            ->where('created_at', 'like', '%'.date('Y-m-d').'%')
            ->first();

        if ($taskSession) {
            $start_time = new DateTime($taskSession->start_time);
            $current_time = new DateTime(date('Y-m-d H:i:s'));
            $diff = $start_time->diff($current_time);
            $secs = ($diff->h * 60 * 60) + ($diff->i * 60) + $diff->s;
            if ($taskSession->current_status == 'resume') {
                $secs = ($taskSession->total * 60) + $secs;
            }

            return response()->json(['flag' => true, 'id' => $taskSession->task_id, 'sec' => $secs]);
        } else {
            $taskSession = TaskSession::select('id', 'task_id', 'created_at')
                ->has('task.project')
                ->where('user_id', Auth::user()->id)
                ->whereIn('current_status', ['started', 'resume', 'pause'])
                ->whereDate('created_at', '<', date('Y-m-d'))
                ->first();
            if ($taskSession) {
                return response()->json(['flag' => false, 'old' => $taskSession->task_id, 'task' => $taskSession->task->title, 'date' => date_format(new DateTime($taskSession->created_at), 'F d, Y')]);
            }
            $task = TaskAssignedUsers::where('task_id', request('task-id'))->where('user_id', Auth::user()->id)->first();
            if ($task) {
                return response()->json(['flag' => false]);
            } else {
                return response()->json(['flag' => false, 'assigned' => 'false']);
            }
        }
    }

    public function checkTaskSession($id = null)
    {
        $taskId = request('task-id') ?? $id;
        $taskSession = TaskSession::select('id', 'start_time', 'task_id', 'current_status', 'total')
            ->has('task.project')
            ->where('user_id', Auth::user()->id)
            ->whereIn('current_status', ['started', 'pause', 'resume'])
            ->where('created_at', 'like', '%'.date('Y-m-d').'%')
            ->when($taskId != null, function ($query) {
                $query->where(['task_id' => request('task-id')]);
            })
            ->first();
        Session::forget('taskRunning');
        Session::forget('taskPaused');
        if (! Session::has('pausedTask')) {
            $pausedTask = TaskSession::where('user_id', Auth::user()->id)
                ->has('task.project')
                ->whereIn('current_status', ['pause'])
                ->where('created_at', 'like', '%'.date('Y-m-d').'%')
                ->pluck('task_id')->toArray();
            Session::put('pausedTask', $pausedTask);
        }
        if ($taskSession) {
            $start_time = new DateTime($taskSession->start_time);
            $current_time = new DateTime(date('Y-m-d H:i:s'));
            $diff = $start_time->diff($current_time);
            $secs = ($diff->h * 60 * 60) + ($diff->i * 60) + $diff->s;
            if ($taskSession->current_status == 'pause') {
                $endTime = new DateTime($taskSession->end_time);
                $current_time = new DateTime(date('Y-m-d H:i:s'));
                $diff = $endTime->diff($current_time);
                $secs = ($diff->h * 60 * 60) + ($diff->i * 60) + $diff->s;
                $secs = ($taskSession->total * 60) + $secs;

                Session::put('taskPaused', (int) $taskSession->task_id);
                $taskList = Session::get('pausedTask');
                if (($key = array_search($taskSession->task_id, $taskList)) === false) {
                    $taskList[] = $taskSession->task_id;
                    Session::put('pausedTask', $taskList);
                }
            } else {
                Session::put('taskRunning', (int) $taskSession->task_id);
            }

            return response()->json(['flag' => true, 'status' => $taskSession->current_status, 'id' => $taskSession->task_id, 'sec' => $secs]);
        } else {
            $task = TaskAssignedUsers::where('task_id', request('task-id'))->where('user_id', Auth::user()->id)->first();
            if ($task) {
                return response()->json(['flag' => false, 'assigned' => 'true']);
            } else {
                return response()->json(['flag' => false, 'assigned' => 'false']);
            }
        }
    }

    public function getLastWorkingDay()
    {
        $i = 1;

        $date = date('d/m/Y', strtotime('-'.$i.' days'));

        while (true) {
            $f = $this->checkHoliday($date);
            if ($f) {
                return $date;
            }
            $i = $i + 1;
            $date = date('d/m/Y', strtotime('-'.$i.' days'));
        }

        return $date;
    }

    public function checkHoliday($date)
    {
        $day = Holiday::where('holiday_date', 'like', '%'.$date.'%')->first();

        if ($day) {
            return false;
        }

        $days = WeekHoliday::all();

        foreach ($days as $day) {
            if (date('l', strtotime($date)) == $day->day) {
                return false;
            }
        }

        return true;
    }

    public function calculateBillableHours()
    {
        $businessDays = new BusinessDays();

        $date = $businessDays->getLastWorkingDay(Carbon::yesterday())->format('d/m/Y');

        $searchDate = date('Y-m-d', strtotime(str_replace('/', '-', $date)));

        $projects = Project::select('id', 'project_name')->orderBy('project_name', 'ASC')->get();
        $clients = Client::select('id', 'company_name')->orderBy('company_name', 'ASC')->get();
        $day = [
            'date' => date('M d l, Y', strtotime($searchDate)),
            'day' => $date,
        ];
        $tasksessiondatas = TaskSession::with('task', 'user', 'task.project')->where('created_at', 'like', '%'.$searchDate.'%')->has('task.project')->has('task')->get();

        $dataset = $this->createDataSet($tasksessiondatas);

        $total = $this->getTotal($tasksessiondatas);

        $users = User::notClients()->active()->orderBy('first_name')->get();

        $holiday = $this->checkHoliday($date);

        $sessionTypes = SessionType::pluck('title', 'slug')->toArray();
        $sessionType = [];
        $excludedProjects = [];

        return view('general.managebillablehours.index', compact('holiday', 'day', 'projects', 'date', 'dataset', 'users', 'clients', 'total', 'sessionType', 'sessionTypes', 'excludedProjects'));
    }

    public function createDataSet($tasksessiondatas)
    {
        $dataset = [];
        foreach ($tasksessiondatas as $data) {
            $task_details = Task::with('children')->where('id', $data->task->id)->first();
            $time = $task_details->estimated_time + $task_details->children->sum('estimated_time');

            $list = [
                'id' => $data->id,
                'project_name' => $data->task->project->project_name,
                'project_id' => $data->task->project->id,
                'user_name' => $data->user->full_name,
                'user_id' => $data->user->id,
                'task_name' => $data->task->title,
                'task_description' => $data->task->description,
                'task_url' => $data->task->task_url,
                'task_id' => $data->task->id,
                'time_spent' => $data->total,
                'estimated_time' => $time,
                'billed_today' => $data->billed_today,
                'date' => $data->created_at,
                'total_weekly' => $data->total_weekly,
                'billed_weekly' => $data->billed_weekly
            ];

            $billed = TaskSession::where('task_id', $data->task->id)->sum('billed_today');

            $list += ['billed_hours' => $billed ? $billed : 0];

            array_push($dataset, $list);
        }

        return $dataset;
    }

    public function getTotal($tasksessiondatas, $billingType = 'Daily')
    {
        $total = ['time_spent' => 0, 'billable' => 0, 'estimated_time' => 0, 'billed' => 0];
        $task_id = [];
        foreach ($tasksessiondatas as $data) {
            if ($billingType == 'Weekly') {
                $total['time_spent'] += $data->total_weekly;
                $total['billable'] += $data->billed_weekly;
            } else {
                $total['time_spent'] += $data->total;
                $total['billable'] += $data->billed_today;
            }
            $total['estimated_time'] += $data->task->estimated_time;
            if (! in_array($data->task->id, $task_id)) {
                $billed = TaskSession::where('task_id', $data->task->id)->sum('billed_today');
                $total['billed'] += $billed ? $billed : 0;
                array_push($task_id, $data->task->id);
            }
        }

        return $total;
    }

    public function searchBillableHours()
    {
        $projects = Project::select('id', 'project_name')->orderBy('project_name', 'ASC')->get();
        $clients = Client::select('id', 'company_name')->orderBy('company_name', 'ASC')->get();

        $date = date('Y-m-d', strtotime(str_replace('/', '-', request('date'))));
        $weekrange = request('week');
        $projectId = request('projectId');
        $userId = request('userId');
        $clientId = request('clientId');
        $sessionType = request('sessionType') ?: [];
        $billingType = request('billingType') ?? 'Daily';
        $excludedProjects = request('excludeProjectId') ?: [];
        $tasksessiondatas = TaskSession::with('task', 'user', 'task.project')->has('task.project')->has('task');

        $day = [];
        $week = [];
        if ($billingType == 'Daily') {
            $day = [
                'date' => date('M d l, Y', strtotime($date)),
                'day' => request('date'),
            ];

            $tasksessiondatas->when(! empty($date), function ($q) use ($date) {
                $q->where('created_at', 'like', '%'.$date.'%');

                return $q;
            });
        } else {
            $startDate = Carbon::createFromFormat('F j, Y', explode(' - ', $weekrange)[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('F j, Y', explode(' - ', $weekrange)[1])->format('Y-m-d');

            $previoustWeekStart = Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY)->format('M d, Y');
            $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY)->format('M d, Y');

            $week = [
                'week' => $weekrange,
                'previousWeek' => $previoustWeekStart.' - '.$previousWeekEnd,
            ];

            $tasksessiondatas->whereBetween('created_at', ["$startDate", "$endDate"])
                                            ->select('id', 'total', 'billed_today', 'task_id', 'user_id', DB::raw('SUM(total) as total_weekly'), DB::raw('SUM(billed_today) as billed_weekly'))
                                            ->groupBy('task_id', 'user_id');
        }

        $tasksessiondatas->when(! empty($projectId), function ($q) use ($projectId) {
            return $q->whereHas('task', function ($q) use ($projectId) {
                $q->where('project_id', '=', $projectId);
            });
        });

        $tasksessiondatas->when(! empty($clientId), function ($q) use ($clientId) {
            return $q->whereHas('task.project', function ($q) use ($clientId) {
                $q->where('client_id', '=', $clientId);
            });
        });

        $tasksessiondatas->when(! empty($userId), function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        });

        $tasksessiondatas->when(! empty($sessionType), function ($q) use ($sessionType) {
            return $q->whereIn('session_type', $sessionType);
        });

        $tasksessiondatas->when(! empty($excludedProjects), function ($q) use ($excludedProjects) {
            return $q->whereHas('task', function ($q) use ($excludedProjects) {
                $q->whereNotIn('project_id', $excludedProjects);
            });
        });

        $tasksessiondatas = $tasksessiondatas->get();

        $dataset = $this->createDataSet($tasksessiondatas);

        $users = User::active()->orderBy('first_name')->get();

        $total = $this->getTotal($tasksessiondatas, $billingType);

        $holiday = $this->checkHoliday($date);

        $date = request('date');

        $sessionTypes = SessionType::pluck('title', 'slug')->toArray();

        $content = view('general.managebillablehours.sheet', compact('holiday', 'day', 'week', 'projects', 'date', 'dataset', 'users', 'clients', 'total', 'billingType', 'sessionType', 'sessionTypes', 'excludedProjects'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function updateBillableHours()
    {
        $validTimeFormat = '/^\d+(:\d+)?$/';
        if (! preg_match($validTimeFormat, request('newBillableValue'))) {
            return response()->json(['success' => false, 'message' => 'Invalid Time Entry']);
        } else {
            $sessionTypes = SessionType::pluck('slug')->toArray();
            $total = request('newBillableValue');
            if (strpos($total, ':') !== false) {
                [$hours, $minutes] = explode(':', $total);
                $billableHours = ($hours * 60) + $minutes;
            } else {
                $billableHours = floor($total * 60);
            }
            if (request('billingType') && request('billingType') == 'Weekly') {
                $data = [
                    'billableValue' => $billableHours,
                    'week' => request('week'),
                    'sessionId' => request('sessionId'),
                    'sessionType' => request('sessionType') ?: $sessionTypes
                ];
                $taskId = $this->updateSessionWeeklyData($data);
            } else {
                $taskId = $this->updateSessionDailyData(request(), $billableHours);
            }
            $this->updateTaskCompletion($taskId);

            return response()->json(['success' => true, 'message' => 'Billable Hours updated successfully', 'time' => $billableHours]);
        }
    }

    public function updateSessionWeeklyData($data)
    {
        $billableValue = $data['billableValue'];
        $startDate = Carbon::createFromFormat('F j, Y', explode(' - ', $data['week'])[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('F j, Y', explode(' - ', $data['week'])[1])->format('Y-m-d');
        $sessionType = $data['sessionType'];
        $taskSessionData = TaskSession::find($data['sessionId']);
        $taskSessions = TaskSession::with('task', 'user', 'task.project')->whereBetween('created_at', ["$startDate", "$endDate"])
                                    ->select('id', 'total', 'billed_today')
                                    ->where('task_id', $taskSessionData['task_id'])
                                    ->where('user_id', $taskSessionData['user_id'])
                                    ->when(! empty($sessionType), function ($q) use ($sessionType) {
                                        return $q->whereIn('session_type', $sessionType);
                                    })
                                    ->whereIn('session_type', $data['sessionType'])
                                    ->orderBy('total', 'desc')->get();

        foreach ($taskSessions as $key => $data) {
            if ($billableValue >= 0) {
                $billedValue = ($data['total'] > $billableValue || $key == count($taskSessions) - 1) ? $billableValue : $data['total'];

                TaskSession::find($data['id'])->update(['billed_today' => $billedValue]);
                $billableValue = $billableValue - $data['total'];
                continue;
            }
            TaskSession::find($data['id'])->update(['billed_today' => 0]);
        }

        return $taskSessionData['task_id'];
    }

    public function updateSessionDailyData($request, $billableHours)
    {
        $taskData = [
            'billed_today' => $billableHours,
        ];
        $taskSession = TaskSession::find(request('sessionId'));
        $taskSessionData['task_id'] = $taskSession['task_id'];
        $taskSession->update($taskData);

        return $taskSessionData['task_id'];
    }

    public function updateTaskCompletion($id)
    {
        $time_spent = TaskSession::where('task_id', $id)->sum('total');

        $data = ['time_spent' => $time_spent / 60];

        if (number_format($time_spent) == 0) {
            $data += ['status' => 'Backlog'];
        }

        Task::find($id)->update($data);
    }

    // public function getTaskSession()
    // {
    //     $taskSession = TaskSession::where('task_id', $id)->with('user')->get();

    //     $content = view('employeetasks.listSession', compact('taskSession'))->render();

    //     $res = [
    //         'status' => 'OK',
    //         'data' => $content,
    //     ];

    //     return response()->json($res);
    // }

    public function checkExistingSession()
    {
        $task = TaskSession::with('task')->whereIn('current_status', ['started', 'pause', 'resume'])->where('user_id', Auth::user()->id)->where('created_at', '<', date('Y-m-d'))->has('task.project')->has('task')->first();

        if ($task) {
            return response()->json(['flag' => true, 'date' => date_format(new DateTime($task->created_at), 'F d, Y'), 'task_id' => $task->task->id, 'title' => $task->task->title]);
        }

        return response()->json(['flag' => false]);
    }

    public function checkIfSessionIsStopped()
    {
        $task = TaskSession::with('task')->whereIn('current_status', ['started', 'pause', 'resume'])->where('user_id', Auth::user()->id)->where('task_id', request('taskId'))->has('task')->first();

        if ($task) {
            return response()->json(['flag' => false]);
        }

        return response()->json(['flag' => true]);
    }

    public function addEasyAccess($id)
    {
        $list = unserialize(Auth::user()->easy_access);

        $set = [];

        array_push($set, ['name' => 'Current Task', 'link' => config('app.url').'/tasks/'.$id]);

        foreach ($list as $item) {
            if ($item['name'] != 'Current Task') {
                array_push($set, ['name' => $item['name'], 'link' => $item['link']]);
            }
        }

        $list = serialize($set);

        User::find(Auth::user()->id)->update(['easy_access' => $list]);

        return true;
    }

    public function removeEasyAccess()
    {
        $list = Auth::user()->easy_access;

        $list = unserialize($list);
        foreach ($list as $key => $item) {
            if ($item['name'] == 'Current Task') {
                array_splice($list, $key, 1);
            }
        }
        $list = serialize($list);
        User::find(Auth::user()->id)->update(['easy_access' => $list]);
        $list = unserialize(User::where('id', Auth::user()->id)->first()->easy_access);

        return true;
    }

    public function updateBulkBillableHours()
    {
        if (request('billingType') && request('billingType') == 'Weekly') {
            $this->bulkUpdateSessionWeeklyData(request());
        } else {
            $this->bulkUpdateSessionDailyData(request());
        }

        return response()->json(['success' => true, 'message' => 'Billable Hours updated successfully']);
    }

    public function bulkUpdateSessionWeeklyData($request)
    {
        $billableValues = $request['newBillableValue'];
        $sessionId = $request['sessionId'];
        foreach ($billableValues as $key => $value) {
            $data = [
                'billableValue' => $value,
                'week' => $request['week'],
                'sessionId' => $sessionId[$key],
            ];

            $taskId = $this->updateBulkSessionWeeklyData($data);
            $this->updateTaskCompletion($taskId);
        }
    }

    public function bulkUpdateSessionDailyData($request)
    {
        $billableValues = $request['newBillableValue'];
        $sessionId = $request['sessionId'];
        foreach ($billableValues as $key => $value) {
            $taskData = [
                'billed_today' => $value,
            ];
            TaskSession::find($sessionId[$key])->update($taskData);
            $this->updateTaskCompletion(TaskSession::find($sessionId[$key])->task_id);
        }
    }

    public function pauseSession(Request $request)
    {
        $taskSession = TaskSession::select('id', 'start_time', 'total', 'comments')
            ->where('task_id', request('task-id'))
            ->where('user_id', Auth::user()->id)
            ->where('created_at', 'like', '%'.date('Y-m-d').'%')
            ->whereIn('current_status', ['started', 'resume'])->first();
        Session::forget('taskRunning');

        $start_time = new DateTime($taskSession->start_time);
        $current_time = new DateTime(date('Y-m-d H:i:s'));
        $diff = $start_time->diff($current_time);
        $min = ($diff->h * 60) + $diff->i;
        $comments = is_null($taskSession->comments ?? null) ? config('general.task.task_session.default_task_pause_message') : $taskSession->comments;
        $data = [
            'current_status' => 'pause',
            'total' => $taskSession->total + $min,
            'end_time' => new DateTime(date('Y-m-d H:i:s')),
            'billed_today' => 0,
            'session_type' => auth()->user()->session_slug ?? config('general.task.task_session.default_session_type'),
            'comments' => $comments
        ];
        if ($taskSession->total + $min > 0) {
            $task = TaskSession::find($taskSession->id);
            $task->update($data);
            Session::put('taskPaused', (int) request('task-id'));
            $taskList = Session::get('pausedTask');
            $taskList[] = (int) request('task-id');
            Session::put('pausedTask', $taskList);
        } else {
            $this->removeTaskFromPauseList($taskSession->id);
            TaskSession::find($taskSession->id)->delete();
        }
        $this->removeEasyAccess();
        $this->updateTaskCompletion(request('task-id'));

        return response()->json(['message' => 'Session ended']);
    }

    public function removeTaskFromPauseList($taskId)
    {
        $taskList = Session::get('pausedTask') ?? [];
        if (($key = array_search($taskId, $taskList)) !== false) {
            unset($taskList[$key]);
        }
        Session::put('pausedTask', $taskList);
        $nextPausedTask = array_pop($taskList) ?? null;
        Session::put('taskPaused', $nextPausedTask);
    }

    private function checkTaskIsRunning()
    {
        $today = date('Y-m-d');
        $isRunning = TaskSession::where('created_at', 'like', '%'.$today.'%')
            ->whereIn('current_status', ['resume', 'started'])
            ->where(['user_id' => Auth::user()->id, 'task_id' => request('task_id')])
            ->count();
        if ($isRunning > 0) {
            Session::put('taskRunning', (int) request('task_id'));
        }
    }

    public function updateBulkSessionWeeklyData($data)
    {
        $billableValue = $data['billableValue'];
        $startDate = Carbon::createFromFormat('F j, Y', explode(' - ', $data['week'])[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('F j, Y', explode(' - ', $data['week'])[1])->format('Y-m-d');
        $taskSessionData = TaskSession::find($data['sessionId']);
        $taskSessions = TaskSession::with('task', 'user', 'task.project')->whereBetween('created_at', ["$startDate", "$endDate"])
                                    ->select('id', 'total', 'billed_today')
                                    ->where('task_id', $taskSessionData['task_id'])
                                    ->where('user_id', $taskSessionData['user_id'])
                                    ->orderBy('total', 'desc')->get();

        foreach ($taskSessions as $key => $data) {
            TaskSession::find($data['id'])->update(['billed_today' => $data['total']]);
        }

        return $taskSessionData['task_id'];
    }
}
