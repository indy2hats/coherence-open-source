<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskSession;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Services\DayService;
use Auth;
use DB;
use Facades\App\Services\UserTimesheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserTimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = date('d-m-Y');
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $days = $this->createDays($date);

        $dataset = [];

        $tasks = TaskSession::with('task', 'task.project', 'user')->where('user_id', Auth::user()->id)->where('created_at', '>=', date('Y-m-d', strtotime($year.'W'.$week.'1')))->where('created_at', '<=', date('Y-m-d', strtotime($year.'W'.$week.'7')))->has('task')->has('task.project')->orderBy('task_id')->get();

        $dataset = $this->createDataset($tasks, $dataset);

        $projects = Project::select('id', 'project_name')->whereHas('task.users', function ($q) {
            $q->where('task_assigned_users.user_id', Auth::user()->id);
        })->orderBy('project_name', 'ASC')->get();

        $allTasks = Task::select('id', 'title')->where('id', Auth::user()->id)->get();

        $holidays = $this->getListUser(date('Y-m-d'));

        $total = $this->createTotalForUser($tasks);

        return view('timesheets.userstatus.index', compact('projects', 'days', 'tasks', 'allTasks', 'dataset', 'holidays', 'total'));
    }

    public function createTotalForUser($tasks)
    {
        $total = ['Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0, 'total' => 0];

        foreach ($tasks as $task) {
            $day = date('D', strtotime($task->created_at));
            $total[$day] += (int) $task->total;
            $total['total'] += (int) $task->total;
        }

        return $total;
    }

    public function getListUser($date)
    {
        $date = date('d-m-Y', strtotime($date));
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $startDate = date('Y-m-d', strtotime($year.'W'.$week.'1'));
        $endDate = date('Y-m-d', strtotime($year.'W'.$week.'7'));

        $days = Holiday::where('holiday_date', '>=', $startDate)->where('holiday_date', '<=', $endDate)->get();

        $list = [];

        foreach ($days as $day) {
            array_push($list, date('N', strtotime($day->holiday_date)));
        }

        $days = WeekHoliday::all();

        foreach ($days as $day) {
            $date = $startDate;
            while ($date <= $endDate) {
                if (date('l', strtotime($date)) == $day->day) {
                    array_push($list, date('N', strtotime($date)));
                }
                $date = date('Y-m-d', strtotime($date.' + 1 days'));
            }
        }

        $leaves = Leave::where('user_id', Auth::user()->id)->where('status', '=', 'Approved')->where('from_date', '>=', $startDate)->where('from_date', '<=', $endDate)->get();

        foreach ($leaves as $leave) {
            $start = $leave->from_date;
            while ($start <= $leave->to_date && $start <= $endDate) {
                if ($start <= $endDate) {
                    array_push($list, date('N', strtotime($start)));
                    $start = date('Y-m-d', strtotime($start.' + 1 days'));
                }
            }
        }

        $leaves = Leave::where('user_id', Auth::user()->id)->where('status', '=', 'Approved')->where('to_date', '>=', $startDate)->where('to_date', '<=', $endDate)->get();

        foreach ($leaves as $leave) {
            $start = $leave->from_date;
            while ($start <= $leave->to_date && $start <= $endDate) {
                if ($start < $startDate) {
                    $start = date('Y-m-d', strtotime($start.' + 1 days'));
                }
                if ($start <= $endDate) {
                    array_push($list, date('N', strtotime($start)));
                    $start = date('Y-m-d', strtotime($start.' + 1 days'));
                }
            }
        }

        return array_unique($list);
    }

    public function getList($date)
    {
        $date = date('d-m-Y', strtotime($date));
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $startDate = date('Y-m-d', strtotime($year.'W'.$week.'1'));
        $endDate = date('Y-m-d', strtotime($year.'W'.$week.'7'));

        $days = Holiday::where('holiday_date', '>=', $startDate)->where('holiday_date', '<=', $endDate)->get();

        $list = [];

        foreach ($days as $day) {
            array_push($list, date('N', strtotime($day->holiday_date)));
        }

        $days = WeekHoliday::all();

        foreach ($days as $day) {
            $date = $startDate;
            while ($date <= $endDate) {
                if (date('l', strtotime($date)) == $day->day) {
                    array_push($list, date('N', strtotime($date)));
                }
                $date = date('Y-m-d', strtotime($date.' + 1 days'));
            }
        }

        return array_unique($list);
    }

    public function getUserLeaves($id, $date)
    {
        $date = date('d-m-Y', strtotime($date));
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $startDate = date('Y-m-d', strtotime($year.'W'.$week.'1'));
        $endDate = date('Y-m-d', strtotime($year.'W'.$week.'7'));

        $list = [];

        $leaves = Leave::where('user_id', $id)->where('status', '=', 'Approved')->where('from_date', '>=', $startDate)->where('from_date', '<=', $endDate)->get();

        foreach ($leaves as $leave) {
            $start = $leave->from_date;
            while ($start <= $leave->to_date && $start <= $endDate) {
                if ($start <= $endDate) {
                    array_push($list, date('N', strtotime($start)));
                    $start = date('Y-m-d', strtotime($start.' + 1 days'));
                }
            }
        }

        $leaves = Leave::where('user_id', $id)->where('status', '=', 'Approved')->where('to_date', '>=', $startDate)->where('to_date', '<=', $endDate)->get();

        foreach ($leaves as $leave) {
            $start = $leave->from_date;
            while ($start <= $leave->to_date && $start <= $endDate) {
                if ($start < $startDate) {
                    $start = date('Y-m-d', strtotime($start.' + 1 days'));
                }
                if ($start <= $endDate) {
                    array_push($list, date('N', strtotime($start)));
                    $start = date('Y-m-d', strtotime($start.' + 1 days'));
                }
            }
        }

        return array_unique($list);
    }

    public function manageEntry(Request $request)
    {
        try {
            if (! UserTimesheetService::validateDate(request('task_id'))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'total' => 'You are trying enter time on a previous date for this task !!'
                ]);
            }

            $response = UserTimesheetService::addTaskSession($request);

            return $response;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function createDays($date)
    {
        $ts = strtotime(str_replace('/', '-', $date));
        $date = str_replace('/', '-', $date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $days = [
            'start' => date('M d l, Y', strtotime($year.'W'.$week.'1')),
            'end' => date('M d l, Y', strtotime($year.'W'.$week.'7')),
            'current_week' => (date_format(date_create($date), 'Y-m-d') >= date('Y-m-d', strtotime('monday this week')) && date_format(date_create($date), 'Y-m-d') <= date('Y-m-d', strtotime('sunday this week'))) ? date('D').' '.date('d') : false,
        ];
        for ($i = 1; $i < 8; $i++) {
            $days['day'.$i] = date('D', strtotime($year.'W'.$week.$i)).' '.date('d', strtotime($year.'W'.$week.$i));
            $days['day'.$i.'_date'] = date('d/m/Y', strtotime($year.'W'.$week.$i));
        }

        return $days;
    }

    public function createDataset($tasks, $dataset)
    {
        foreach ($tasks as $task) {
            $saveStatus = false;
            foreach ($dataset as $data) {
                if ($data['task_id'] == $task->task_id) {
                    $day = date('N', strtotime($task->created_at));
                    $data['day'.$day] = $task->total;
                    $data['day'.$day.'_id'] = $task->id;
                    $saveStatus = true;
                    break;
                }
            }
            if (! $saveStatus) {
                array_push($dataset, [
                    'user' => $task->user->full_name,
                    'project_id' => $task->task->project->id,
                    'project_name' => $task->task->project->project_name,
                    'task_id' => $task->task_id,
                    'task_title' => $task->task->title,
                    'task_description' => $task->task->description,
                    'task_start_date' => $task->task->start_date_format,
                    'day1' => 0,
                    'day1_id' => '',
                    'editable_1' => 0,
                    'day2' => 0,
                    'day2_id' => '',
                    'editable_2' => 0,
                    'day3' => 0,
                    'day3_id' => '',
                    'editable_3' => 0,
                    'day4' => 0,
                    'day4_id' => '',
                    'editable_4' => 0,
                    'day5' => 0,
                    'day5_id' => '',
                    'editable_5' => 0,
                    'day6' => 0,
                    'day6_id' => '',
                    'editable_6' => 0,
                    'day7' => 0,
                    'day7_id' => '',
                    'editable_7' => 0
                ]);
            }
            $day = date('N', strtotime($task->created_at));
            $date = Carbon::parse($task->created_at);
            $now = Carbon::now();
            $secondLastDay = \Carbon\Carbon::parse(DayService::getNthLastWorkingday(2))->format('Y-m-d 00:00:00');
            $diff = $date->gte($secondLastDay);
            $data['editable_'.$day] = 2;
            $dataset[sizeof($dataset) - 1]['day'.$day] = (int) $task->total;
            $dataset[sizeof($dataset) - 1]['day'.$day.'_id'] = (int) $task->id;
            $dataset[sizeof($dataset) - 1]['editable_'.$day] = $diff ? 1 : 0;
        }

        // dd($dataset);
        return $dataset;
    }

    public function createTotal($tasks)
    {
        $total = ['Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0, 'total' => 0];

        foreach ($tasks as $task) {
            $day = date('D', strtotime($task->created_at));
            $total[$day] += (int) $task->total;
            $total['total'] += (int) $task->total;
        }

        return $total;
    }

    public function userTimesheetSearch()
    {
        $tasks = $this->searchResult(Auth::user()->id, date('Y-m-d', strtotime(str_replace('/', '-', request('date')))), request('projectId'));

        $days = $this->createDays(request('date'));

        $dataset = [];

        $dataset = $this->createDataset($tasks, $dataset);

        $projects = Project::select('id', 'project_name')->whereHas('task.users', function ($q) {
            $q->where('task_assigned_users.user_id', Auth::user()->id);
        })->orderBy('project_name', 'ASC')->get();

        $allTasks = Task::select('id', 'title')->where('id', Auth::user()->id)->get();

        $holidays = $this->getListUser(date('Y-m-d', strtotime(str_replace('/', '-', request('date')))));

        $total = $this->createTotalForUser($tasks);

        $res = [
            'status' => 'ERROR',
            'data' => '',

        ];

        $content = view('timesheets.userstatus.timesheet', compact('projects', 'days', 'tasks', 'allTasks', 'dataset', 'holidays', 'total'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function searchResult($userId, $date, $projectId)
    {
        $date = date('d-m-Y', strtotime(str_replace('/', '-', $date)));
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $tasks = TaskSession::with('task', 'task.project', 'user')->has('task')->has('task.project');

        $tasks->when(! empty($userId), function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        $tasks->when(! empty($date), function ($q) use ($year, $week) {
            $q->where('created_at', '>=', date('Y-m-d', strtotime($year.'W'.$week.'1')));
            $q->where('created_at', '<=', date('Y-m-d', strtotime($year.'W'.$week.'7')));
            $q->orderBy('task_id');

            return $q;
        });

        $tasks->when(! empty($projectId), function ($q) use ($projectId) {
            return $q->whereHas('task', function ($q) use ($projectId) {
                $q->where('project_id', '=', $projectId);
            });
        });

        $tasks = $tasks->get();

        return $tasks;
    }

    public function adminTimesheetSearchUser()
    {
        $userId = request('userId');

        $day = request('date');

        $leaves = $this->getUserLeaves($userId, date('Y-m-d', strtotime(str_replace('/', '-', $day))));

        $tasks = $this->searchResult(request('userId'), date('Y-m-d', strtotime(str_replace('/', '-', request('date')))), '');

        $dataset = [];

        $days = $this->createDays(request('date'));

        $dataset = $this->createDataset($tasks, $dataset);

        $total = $this->createTotal($tasks);

        $users = User::active()->select('id', 'first_name', 'last_name')->get();

        $holidays = $this->getList(date('Y-m-d', strtotime(str_replace('/', '-', request('date')))));

        $res = [
            'status' => 'ERROR',
            'data' => '',

        ];

        $content = view('timesheets.usersheet.managesheet', compact('leaves', 'days', 'tasks', 'users', 'dataset', 'day', 'userId', 'total', 'holidays'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function adminTimesheetSearchClient()
    {
        $clientId = request('clientId');

        $date = date('Y-m-d', strtotime(str_replace('/', '-', request('date'))));

        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $startDate = date('Y-m-d', strtotime($year.'W'.$week.'1'));
        $endDate = date('Y-m-d', strtotime($year.'W'.$week.'7'));

        $tasks = DB::select('select projects.id,task_sessions.created_at,projects.project_name, sum(task_sessions.billed_today) as billed_today,sum(total) as total from clients join projects on projects.client_id=clients.id and clients.id=? join tasks on tasks.project_id = projects.id inner join task_sessions on tasks.id=task_sessions.task_id where task_sessions.created_at >= ? and task_sessions.created_at <= ? group by task_sessions.created_at,projects.id order by clients.company_name,projects.project_name,task_sessions.created_at', [$clientId, $startDate, $endDate]);

        $days = $this->createDays(request('date'));

        $dataset = [];

        $total = ['Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0, 'total' => 0];

        foreach ($tasks as $task) {
            $day = date('D', strtotime($task->created_at));
            $total[$day] += (int) $task->total;
            $total['total'] += (int) $task->total;
        }

        foreach ($tasks as $task) {
            $saveStatus = false;
            foreach ($dataset as $data) {
                if ($data['project_id'] == $task->id) {
                    $day = date('N', strtotime($task->created_at));
                    $temp = $data['day'.$day];
                    $data['day'.$day] = $temp + $task->total;
                    $saveStatus = true;
                    break;
                }
            }
            if (! $saveStatus) {
                array_push($dataset, [
                    'project_id' => $task->id,
                    'project_name' => $task->project_name,
                    'day1' => 0,
                    'day2' => 0,
                    'day3' => 0,
                    'day4' => 0,
                    'day5' => 0,
                    'day6' => 0,
                    'day7' => 0,
                ]);
            }
            $day = date('N', strtotime($task->created_at));
            $dataset[sizeof($dataset) - 1]['day'.$day] = $dataset[sizeof($dataset) - 1]['day'.$day] + (int) $task->total;
        }

        $clients = Client::select('id', 'company_name')->get();

        $res = [
            'status' => 'ERROR',
            'data' => '',

        ];

        $holidays = $this->getList(date('Y-m-d', strtotime(str_replace('/', '-', request('date')))));

        $content = view('timesheets.clientsheet.managesheet', compact('days', 'tasks', 'clients', 'dataset', 'total', 'holidays'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function adminTimesheetSearchProject()
    {
        $tasks = $this->searchResult('', date('Y-m-d', strtotime(str_replace('/', '-', request('date')))), request('projectId'));

        $days = $this->createDays(request('date'));

        $dataset = [];

        $total = $this->createTotal($tasks);

        $holidays = $this->getList(date('Y-m-d', strtotime(str_replace('/', '-', request('date')))));

        foreach ($tasks as $task) {
            $saveStatus = false;
            foreach ($dataset as $data) {
                if ($data['task_id'] == $task->task_id && $data['user'] == $task->user->full_name) {
                    $day = date('N', strtotime($task->created_at));
                    $data['day'.$day] = $task->total;
                    $data['day'.$day.'_id'] = $task->id;
                    $saveStatus = true;
                    break;
                }
            }
            if (! $saveStatus) {
                array_push($dataset, [
                    'user' => $task->user->full_name,
                    'project_id' => $task->task->project->id,
                    'project_name' => $task->task->project->project_name,
                    'task_id' => $task->task_id,
                    'task_title' => $task->task->title,
                    'task_description' => $task->task->description,
                    'day1' => 0,
                    'day1_id' => '',
                    'day2' => 0,
                    'day2_id' => '',
                    'day3' => 0,
                    'day3_id' => '',
                    'day4' => 0,
                    'day4_id' => '',
                    'day5' => 0,
                    'day5_id' => '',
                    'day6' => 0,
                    'day6_id' => '',
                    'day7' => 0,
                    'day7_id' => '',
                ]);
            }
            $day = date('N', strtotime($task->created_at));
            $dataset[sizeof($dataset) - 1]['day'.$day] = (int) $task->total;
            $dataset[sizeof($dataset) - 1]['day'.$day.'_id'] = (int) $task->id;
        }

        $projects = Project::select('id', 'project_name')->get();

        $res = [
            'status' => 'ERROR',
            'data' => '',

        ];

        $content = view('timesheets.projectsheet.managesheet', compact('projects', 'days', 'tasks', 'dataset', 'total', 'holidays'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function viewSheetUser()
    {
        $userId = request('id');

        $dateVal = request('date') ? date('d/m/Y', strtotime(request('date'))) : date('d/m/Y');

        $tasks = null;

        $dataset = [];

        $total = [];

        $users = User::notClients()->active()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();

        $holidays = $this->getList($dateVal);

        $leaves = $this->getUserLeaves($userId, $dateVal);

        if ($userId) {
            $tasks = $this->searchResult($userId, $dateVal, '');

            $dataset = [];

            $days = $this->createDays($dateVal);

            $dataset = $this->createDataset($tasks, $dataset);

            $total = $this->createTotal($tasks);

            $holidays = $this->getList(date('d-m-Y', strtotime($dateVal)));
        }

        $ts = strtotime($dateVal);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $days = $this->createDays($dateVal);

        $day = $dateVal;

        return view('timesheets.usersheet.index', compact('leaves', 'dataset', 'days', 'users', 'tasks', 'day', 'userId', 'total', 'holidays'));
    }

    public function viewSheetProject()
    {
        $projects = Project::select('id', 'project_name')->orderBy('project_name', 'ASC')->get();

        $dataset = [];

        $total = [];

        $date = date('d/m/Y');
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $days = $this->createDays($date);

        $day = date('d/m/Y');

        $holidays = $this->getList(date('d-m-Y'));

        return view('timesheets.projectsheet.index', compact('dataset', 'projects', 'days', 'total', 'holidays'));
    }

    public function viewSheetClient()
    {
        $clients = Client::select('id', 'company_name')->orderBy('company_name', 'ASC')->get();

        $dataset = [];

        $total = [];

        $date = date('d/m/Y');
        $ts = strtotime($date);
        $year = date('o', $ts);
        $week = date('W', $ts);

        $days = $this->createDays($date);

        $day = date('d/m/Y');

        $holidays = $this->getList(date('d-m-Y'));

        return view('timesheets.clientsheet.index', compact('dataset', 'days', 'clients', 'total', 'holidays'));
    }
}
