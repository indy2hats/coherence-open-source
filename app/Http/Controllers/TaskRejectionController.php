<?php

namespace App\Http\Controllers;

use App\Models\QaIssue;
use App\Models\Task;
use App\Models\TaskAssignedUsers;
use App\Models\TaskRejection;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskRejectionController extends Controller
{
    public function rejectionUpdate()
    {
        request()->validate([
            'severity' => 'required',
            'reason' => 'required',
        ]);

        $scores = config('rejection');

        $data = [
            'severity' => request('severity'),
            'comments' => request('comments'),
            'score' => $scores[strtolower(request('severity'))],
            'rejected_by' => Auth::user()->id
        ];

        $reasons = [];
        $reasons = array_filter($reasons);
        if (! empty(request('reason'))) {
            $reasons = array_unique(array_merge($reasons, request('reason')));
        }

        $data += ['reason' => implode('_', $reasons)];

        $taskRejection = TaskRejection::find(request('id'));
        $taskRejection->update($data);

        Task::find($taskRejection->task_id)->update(['status' => 'In Progress', 'percent_complete' => 20]);

        if (request('exceed_reason')) {
            TaskAssignedUsers::where('task_id', $taskRejection->task_id)->where('user_id', $taskRejection->user_id)->update(['exceed_reason' => request('exceed_reason')]);
        }

        return response()->json(['message' => 'Task Rejected successfully']);
    }

    public function index(Request $request)
    {
        $users = User::notClients()->orderBy('first_name', 'ASC')->get();
        $qaIssues = QaIssue::orderBy('title', 'ASC')->get();
        if ($request->ajax()) {
            $taskRejections = TaskRejection::leftJoin('task_assigned_users', function ($join) {
                $join->on('task_rejections.task_id', '=', 'task_assigned_users.task_id');
                $join->on('task_rejections.user_id', '=', 'task_assigned_users.user_id');
            })->with('task', 'users')->has('task')->orderBy('task_rejections.updated_at', 'DESC')->select('task_rejections.*', 'task_assigned_users.exceed_reason');

            return DataTables::of($taskRejections)
                ->filter(function ($instance) use ($request) {
                    if (! empty($request->get('daterange'))) {
                        $daterange = explode(' - ', $request->get('daterange'));
                        $fromDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
                        $toDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
                        $instance->whereDate('task_rejections.updated_at', '>=', $fromDate)
                            ->whereDate('task_rejections.updated_at', '<=', $toDate);
                    }
                    if (! empty($request->get('by_user'))) {
                        $instance->whereHas('users', function ($query) use ($request) {
                            $query->where('id', $request->get('by_user'));
                        });
                    }

                    if (! empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $searchKeyword = $request->get('search');
                            $query->whereHas('task', function ($query) use ($searchKeyword) {
                                $query->where('title', 'like', '%'.$searchKeyword['value'].'%');
                            });
                            $query->orwhere(function ($query) use ($searchKeyword) {
                                $query->whereHas('users', function ($query) use ($searchKeyword) {
                                    $query->where('first_name', 'like', '%'.$searchKeyword['value'].'%');
                                    $query->orwhere('last_name', 'like', '%'.$searchKeyword['value'].'%');
                                });
                            });
                            $query->orwhere('severity', 'like', '%'.$searchKeyword['value'].'%');
                            $query->orwhere(function ($query) use ($searchKeyword) {
                                $query->whereHas('qaIssue', function ($query) use ($searchKeyword) {
                                    $query->where('title', 'like', '%'.$searchKeyword['value'].'%');
                                });
                            });
                            $query->orwhere('comments', 'like', '%'.$searchKeyword['value'].'%');
                            $query->orwhere('exceed_reason', 'like', '%'.$searchKeyword['value'].'%');
                        });
                    }
                })

                ->addColumn('reportedDate', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d/m/Y');
                })->addColumn('action', function ($row) {
                    return '<a class="dropdown-item delete-feedback" href="#" data-toggle="modal"
                    data-target="#delete_feedback" data-id="'.$row->id.'" data-tooltip="tooltip"
                    data-placement="top" title="Delete"><i class="fa fa-trash-o m-r-5"></i></a>';
                })
                ->addColumn('user', function ($row) {
                    return $row->users->full_name;
                })
                ->addColumn('task', function ($row) {
                    return $row->task->title;
                })
                ->addColumn('severity', function ($row) {
                    $severityStyle = config('style.qa-feedback.severity');
                    $severityStyle = $severityStyle[strtolower($row->severity) ?: 'default'];

                    return  isset($row->severity) ? '<span class="label '.$severityStyle.'">'.$row->severity.'</span>' : '';
                })
                ->addColumn('reason', function ($row) {
                    return isset($row->qaIssue->title) ? '<span class="label label-plain block">'.$row->qaIssue->title.'</span>' : '';
                })
                ->addColumn('exceedReason', function ($row) {
                    return $row->exceed_reason ?? '';
                })
                ->addColumn('comments', function ($row) {
                    return $row->comments ?? '';
                })

                ->rawColumns(['action', 'severity', 'reason', 'exceedReason', 'comments'])
                ->make(true);
        }

        return view('qa-feedback.index', compact('users', 'qaIssues'));
    }

    public function destroy($id)
    {
        TaskRejection::find($id)->delete();

        $qaIssues = QaIssue::orderBy('title', 'ASC')->get();

        $taskRejections = TaskRejection::with('task', 'users')->leftJoin('task_assigned_users as tau', function ($join) {
            $join->on('task_rejections.user_id', '=', 'tau.user_id');
            $join->on('task_rejections.task_id', '=', 'tau.task_id');
        })->has('task')->get();

        $content = view('qa-feedback.list', compact('taskRejections', 'qaIssues'))->render();

        $res = [
            'status' => 'OK',
            'message' => 'Feedback Deleted successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function store(Request $request)
    {
        request()->validate([
            'search_task_name' => 'required',
            'user' => 'required',
            'severity' => 'required',
            'reason' => 'required',
            'comments' => 'required'
        ]);

        $scores = config('rejection');

        $data = [
            'task_id' => request('search_task_name'),
            'user_id' => request('user'),
            'severity' => request('severity'),
            'comments' => request('comments'),
            'score' => $scores[strtolower(request('severity'))],
            'rejected_by' => Auth::user()->id
        ];

        $reasons = [];
        $reasons = array_filter($reasons);
        if (! empty(request('reason'))) {
            $reasons = array_unique(array_merge($reasons, request('reason')));
        }

        $data += ['reason' => implode('_', $reasons)];

        TaskRejection::create($data);

        $qaIssues = QaIssue::orderBy('title', 'ASC')->get();

        $taskRejections = TaskRejection::with('task', 'users')->leftJoin('task_assigned_users as tau', function ($join) {
            $join->on('task_rejections.user_id', '=', 'tau.user_id');
            $join->on('task_rejections.task_id', '=', 'tau.task_id');
        })->has('task')->get();

        $content = view('qa-feedback.list', compact('taskRejections', 'qaIssues'))->render();

        $res = [
            'status' => 'OK',
            'message' => 'Feedback Deleted successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function userFeedbackSearch()
    {
        $taskRejections = TaskRejection::with('task', 'users');

        if (request('by_user') != '') {
            $taskRejections = $taskRejections->where('task_rejections.user_id', request('by_user'));
        }

        if (request('daterange') != '') {
            $daterange = explode(' - ', request('daterange'));
            $fromDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
            $toDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
            $taskRejections = $taskRejections->whereDate('task_rejections.updated_at', '>=', $fromDate)->whereDate('task_rejections.updated_at', '<=', $toDate);
        }

        $taskRejections = $taskRejections->leftJoin('task_assigned_users as tau', function ($join) {
            $join->on('task_rejections.user_id', '=', 'tau.user_id');
            $join->on('task_rejections.task_id', '=', 'tau.task_id');
        })->get();

        $qaIssues = QaIssue::orderBy('title', 'ASC')->get();

        $content = view('qa-feedback.list', compact('taskRejections', 'qaIssues'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function rejectionQaUpdate()
    {
        request()->validate([
            'severity' => 'required',
            'reason' => 'required',
            'task_rejected_users' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! is_array($value) || count($value) === 0) {
                        $fail('At least one user must be selected.');
                    }
                },
            ],
        ]);

        $scores = config('rejection');
        $reasons = [];
        $reasons = array_filter($reasons);
        if (! empty(request('reason'))) {
            $reasons = array_unique(array_merge($reasons, request('reason')));
        }
        foreach (request('task_rejected_users') as $rejectedUser) {
            $data = [];
            $data = [
                'user_id' => $rejectedUser,
                'task_id' => request('id'),
                'severity' => request('severity'),
                'comments' => request('comments'),
                'score' => $scores[strtolower(request('severity'))],
                'rejected_by' => Auth::user()->id
            ];

            $data += ['reason' => implode('_', $reasons)];

            $taskRejection = TaskRejection::create($data);
        }

        return response()->json(['message' => 'Task Rejected successfully']);
    }

    public function deleteRejection($id)
    {
        $taskRejection = TaskRejection::find($id);

        if (! $taskRejection) {
            return response()->json(['status' => false, 'message' => 'Task rejection not found']);
        }

        if ($taskRejection->rejected_by != Auth::user()->id) {
            return response()->json(['status' => false, 'message' => 'Unauthorised to delete this task rejection']);
        }

        $taskRejection->delete();

        return response()->json(['status' => true, 'message' => 'Task rejection deleted successfully']);
    }
}
