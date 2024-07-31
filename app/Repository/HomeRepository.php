<?php

namespace App\Repository;

use App\Models\Client;
use App\Models\Comment;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskRejection;
use App\Models\TaskSession;
use App\Models\User;
use App\Services\LeaveService;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class HomeRepository
{
    use GeneralTrait;
    protected $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function getUser()
    {
        return User::findOrFail(request('user_id'));
    }

    public function search($request)
    {
        $term = $request->input('q');
        $isClient = $this->isClient();
        $canViewProjects = $this->getCurrentUser()->can('view-projects');
        $currentUserId = $this->getCurrentUserId();
        $content = [];
        $clientProjects = [];
        $clientTasks = [];

        if (! empty($term) && strlen($term) > 2) {
            if ($isClient) {
                $clientProjects = $this->getClientProjects($currentUserId);
                $clientTasks = $this->getClientTasks($clientProjects);
            }

            $results = (new Search())
                ->registerModel(Task::class, function (ModelSearchAspect $modelSearchAspect) use ($isClient, $clientProjects, $term) {
                    $modelSearchAspect->addSearchableAttribute('title')
                        ->addSearchableAttribute('task_id') // Allow searching in both 'title' and 'task_id'
                        ->where(function ($query) use ($term) {
                            $query->where('title', 'like', "%$term%")
                                ->orWhere('task_id', 'like', "%$term%");
                        })
                        ->has('project')
                        ->orderBy('title', 'ASC')
                        ->take(10);
                    if ($isClient) {
                        $modelSearchAspect->whereIn('project_id', $clientProjects);
                    }
                })
                ->registerModel(Project::class, function (ModelSearchAspect $modelSearchAspect) use ($clientProjects, $isClient, $term) {
                    $modelSearchAspect->addSearchableAttribute('project_name')
                        ->where('project_name', 'like', "%$term%")
                        ->orderBy('project_name', 'ASC')->take(10);
                    if ($isClient) {
                        $modelSearchAspect->whereIn('id', $clientProjects);
                    }
                })
                ->registerModel(Comment::class, function (ModelSearchAspect $modelSearchAspect) use ($isClient, $clientTasks, $term) {
                    $modelSearchAspect->addSearchableAttribute('comment')
                        ->where('comment', 'like', "%$term%")
                        ->orderBy('comment', 'ASC')->take(10);
                    if ($isClient) {
                        $modelSearchAspect->whereIn('commentable_id', $clientTasks);
                    }
                })
                ->perform($term);

            $content = view('partials.global-search-result', compact('results'))->render();
        }

        $data = [
            'status' => true,
            'data' => $content
        ];

        return $data;
    }

    public function getClientProjects($currentUserId)
    {
        return Project::whereHas('client', function ($q) use ($currentUserId) {
            $q->where('user_id', $currentUserId);
        })->pluck('id')->toArray();
    }

    public function getClientTasks($clientProjects)
    {
        return Task::whereIn('project_id', $clientProjects)->has('project')->pluck('id')->toArray();
    }

    public function getDetails()
    {
        return User::with('users_project', 'role')->where('id', $this->getCurrentUserId())->first();
    }

    public function getCountDetails()
    {
        if ($this->isClient()) {
            $tasks = Task::with(['project.client' => function ($q) {
                $q->where('user_id', $this->getCurrentUserId());
            }])->whereHas('project.client', function ($query) {
                $query->where('user_id', $this->getCurrentUserId());
            })->notArchived()->get();
        } else {
            $tasks = Task::with(['users' => function ($q) {
                $q->where('user_id', '=', $this->getCurrentUserId());
            }])->whereHas('users', function ($query) {
                $query->where('user_id', '=', $this->getCurrentUserId());
            })->notArchived()->get();
        }

        $counts['total'] = count($tasks);
        $counts['upcoming'] = 0;
        $counts['ongoing'] = 0;
        $counts['completed'] = 0;
        foreach ($tasks as $task) {
            if ($task->status == 'Backlog') {
                $counts['upcoming'] += 1;
            } elseif ($task->status == 'Done') {
                $counts['completed'] += 1;
            } else {
                $counts['ongoing'] += 1;
            }
        }

        return $counts;
    }

    public function getTotalHours()
    {
        if ($this->isClient()) {
            $tasks = Task::with(['project.client' => function ($q) {
                $q->where('user_id', $this->getCurrentUserId());
            }])->whereHas('project.client', function ($query) {
                $query->where('user_id', $this->getCurrentUserId());
            })->pluck('id')->toArray();

            $taskIds = implode(',', $tasks);
            if ($taskIds) {
                return DB::select("select sum(billed_today) as billed, sum(total) as total from task_sessions where task_id IN ($taskIds)")[0];
            }
        }

        return DB::select('select sum(billed_today) as billed, sum(total) as total from task_sessions where user_id = ?', [$this->getCurrentUserId()])[0];
    }

    public function getThisWeek()
    {
        if ($this->isClient()) {
            $tasks = TaskSession::with(['task.project.client' => function ($q) {
                $q->where('user_id', $this->getCurrentUserId());
            }])->whereHas('task.project.client', function ($query) {
                $query->where('user_id', $this->getCurrentUserId());
            })
                ->where('created_at', '>=', $this->getStartOfWeek())
                ->where('created_at', '<=', $this->getEndOfWeek())->get();
        } else {
            $tasks = TaskSession::where('user_id', $this->getCurrentUserId())->where('created_at', '>=', $this->getStartOfWeek())->where('created_at', '<=', $this->getEndOfWeek())->get();
        }

        $total = ['Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0, 'total' => 0];

        foreach ($tasks as $task) {
            $day = date('D', strtotime($task->created_at));
            $total[$day] += (int) $task->total;
            $total['total'] += (int) $task->total;
        }

        return $total;
    }

    public function getStartOfWeek()
    {
        return Carbon::now()->startOfWeek()->format('Y-m-d');
    }

    public function getEndOfWeek()
    {
        return Carbon::now()->endOfWeek()->format('Y-m-d');
    }

    public function getLeavesList()
    {
        return Leave::where('user_id', $this->getCurrentUserId())->where('status', 'Approved')->get();
    }

    public function getLeaves()
    {
        $leaves = ['casual' => 0, 'medical' => 0, 'lop' => 0];

        $list = $this->getLeavesList();

        foreach ($list as $item) {
            $leaveCount = $this->leaveService->getLeaveDaysCount($item->from_date, $item->to_date, $item->session);

            if (($item->type == 'Casual') && ($item->lop == 'No')) {
                $leaves['casual'] += $leaveCount;
            } elseif (($item->type == 'Medical') && ($item->lop == 'No')) {
                $leaves['medical'] += $leaveCount;
            } elseif ($item->lop == 'Yes') {
                $leaves['lop'] += $leaveCount;
            }
        }

        return $leaves;
    }

    public function getRejectionCount()
    {
        return TaskRejection::where('user_id', $this->getCurrentUserId())->where('reason', '!=', '')->has('task')->count();
    }

    public function getRejections()
    {
        $list = $this->getTaskRejectionList();

        $rejections = ['low' => 0, 'medium' => 0, 'high' => 0, 'critical' => 0];

        foreach ($list as $item) {
            if ($item->severity == 'Low') {
                $rejections['low'] += 1;
            } elseif ($item->severity == 'Medium') {
                $rejections['medium'] += 1;
            } elseif ($item->severity == 'High') {
                $rejections['high'] += 1;
            } elseif ($item->severity == 'Critical') {
                $rejections['critical'] += 1;
            }
        }

        return $rejections;
    }

    public function getRejectionIndex()
    {
        $list = $this->getTaskRejectionList();

        $values = config('rejection');

        $rejectionIndex = 0;

        foreach ($list as $item) {
            if ($item->severity == 'Low') {
                $rejectionIndex += $values['low'];
            } elseif ($item->severity == 'Medium') {
                $rejectionIndex += $values['medium'];
            } elseif ($item->severity == 'High') {
                $rejectionIndex += $values['high'];
            } elseif ($item->severity == 'Critical') {
                $rejectionIndex += $values['critical'];
            }
        }

        return $rejectionIndex;
    }

    public function getTaskRejectionList()
    {
        return TaskRejection::where('user_id', $this->getCurrentUserId())->where('reason', '!=', '')->has('task')->get();
    }

    public function getClientProjectsCount()
    {
        return Project::whereHas('client', function ($q) {
            $q->where('user_id', $this->getCurrentUserId());
        })->count();
    }

    public function getClientCompanies()
    {
        return Client::with('project')->where('user_id', $this->getCurrentUserId())->orderBy('company_name', 'ASC')->get();
    }
}
