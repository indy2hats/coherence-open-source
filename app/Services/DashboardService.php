<?php

namespace App\Services;

use App\Repository\DashboardRepository;
use App\Repository\UserRepository;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;

class DashboardService
{
    use GeneralTrait;

    protected $userRepository;
    protected $leaveService;
    protected $dashboardRepository;

    public function __construct(UserRepository $userRepository, LeaveService $leaveService, DashboardRepository $dashboardRepository)
    {
        $this->userRepository = $userRepository;
        $this->leaveService = $leaveService;
        $this->dashboardRepository = $dashboardRepository;
    }

    public function setUserList($users, $request)
    {
        $dataArray = [];
        if (! $users) {
            return $dataArray;
        }
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'userName',
            1 => 'projectName',
            2 => 'taskTitle',
            3 => 'jiraId',
            4 => 'estimatedTime',
            5 => 'timeSpent',
            6 => 'totalTimeSpent',
            7 => 'deadline',
        ];
        $sortColumn = $columns[$request->input('order.0.column')];
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';

        foreach ($users as $user) {
            $userTotalTimeSpent = $user->task->tasks_session()->where('user_id', $user->user_id)->sum('total');
            $timeSpentOnTask = $this->getTimeSpentOnTask($user);
            if (in_array(auth()->user()->role->name, config('general.task_actual_estimate.view_roles'))) {
                $estimatedTime = $user->task->actual_estimated_time;
            } else {
                $estimatedTime = $user->task->estimated_time;
            }
            $dataArray[] = [
                'userId' => $user->user->id,
                'userName' => $user->user->full_name,
                'projectId' => $user->task->project->id,
                'projectName' => $user->task->project->project_name,
                'taskId' => $user->task->id,
                'taskTitle' => $user->task->title,
                'jiraId' => $user->task->task_id,
                'jiraUrl' => $user->task->task_url,
                'taskDescription' => strip_tags($user->task->description),
                'estimatedTime' => $estimatedTime,
                'timeSpent' => $userTotalTimeSpent,
                'startTime' => $user->start_time,
                'activeStartTimes' => $timeSpentOnTask['activeStartTimes'],
                'totalTimeSpent' => $timeSpentOnTask['totalTimeSpent'],
                'activeSessions' => $timeSpentOnTask['activeSessions'],
                'deadline' => $user->task->end_date_format,
            ];
        }
        $dataArray = collect($dataArray)->$sort($sortColumn)
                                        ->skip($offset)
                                        ->take($limit);

        return $dataArray;
    }

    /**
     * formats the list in data table format.
     *
     * @param  mixed  $users
     * @return array
     */
    public function formatUserList($users)
    {
        $dataArray = [];
        if (! $users) {
            return $dataArray;
        }
        foreach ($users as $user) {
            $name = '<a href='.'../weekly-report/users/'.$user['userId'].'>'.$user['userName'].'</a>';
            $project = '<a href='.'/projects/'.$user['projectId'].'>'.$user['projectName'].'</a>';
            $task = '<a data-html="true" data-toggle="tooltip" data-placement="right"
                     title="Description : '.$user['taskDescription'].'" href='.'/tasks/'.$user['taskId'].'>'.$user['taskTitle'].'</a>';
            $jiraId = '<a target="_blank" href='.$user['jiraUrl'].'>'.$user['jiraId'].'</a>';
            $timeSpent = '<div data-total="'.$user['timeSpent'].'" data-start="'.$user['startTime'].'" class="time">
                          <span class="timer"><img src="'.url('images/timer.png').'" style="width:25px;"></span></div>';
            $totalTimeSpent = '<div class="time-taken" data-starts="'.implode(',', $user['activeStartTimes']).'" data-total="'.$user['totalTimeSpent'].'" data-count="'.$user['activeSessions']->count().'" >
            <span><img src="'.url('images/timer.png').'" style="width:25px;"></span></div>';
            $route = route('viewSheetUser', $user['userId']);
            $action = '<a href="'.$route.'"> <i data-toggle="modal" data-target="#add_task_time" class="ri-eye-line" aria-hidden="true"></i></a>';
            $dataArray[] = [
                'name' => $name,
                'project' => $project,
                'task' => $task,
                'jiraId' => $jiraId,
                'estimatedTime' => floor(($user['estimatedTime'] * 60) / 60).'h '.(($user['estimatedTime'] * 60) % 60).'m',
                'timeSpent' => $timeSpent,
                'totalTimeSpent' => $totalTimeSpent,
                'deadline' => $user['deadline'],
                'action' => $action
            ];
        }

        return $dataArray;
    }

    public function getTimeSpentOnTask($user)
    {
        $dataArray = [];
        $activeSessions = $user->task->tasks_session()->whereDay('start_time', '=', date('d'))->whereIn('current_status', ['started', 'resume'])->get();
        $activeStartTimes = $activeSessions->pluck('start_time')->toArray();
        $totalTimeSpent = $user->task->tasks_session()->sum('total');
        $dataArray = [
            'activeSessions' => $activeSessions,
            'activeStartTimes' => $activeStartTimes,
            'totalTimeSpent' => $totalTimeSpent,
        ];

        return $dataArray;
    }

    public function setIdleUserList($users, $request)
    {
        $dataArray = [];
        if (! $users) {
            return $dataArray;
        }
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'first_name',
        ];
        $sortColumn = $columns[$request->input('order.0.column')];
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';

        $dataArray = $users->$sort($sortColumn)
                           ->skip($offset)
                           ->take($limit);

        return $dataArray;
    }

    public function formatIdleUserList($users)
    {
        $dataArray = [];
        if (! $users) {
            return $dataArray;
        }
        foreach ($users as $user) {
            $name = '<a href='.'../weekly-report/users/'.$user->id.'>'.$user->first_name.' '.$user->last_name.'</a><a class="send-alert pull-right" data-id="'.$user->id.'"><i class="fa fa-bell-o"></i> alert</a>';
            $dataArray[] = [
                'name' => $name,
            ];
        }

        return $dataArray;
    }

    public function setOnLeaveUserList($leaves, $request)
    {
        $dataArray = [];
        if (! $leaves) {
            return $dataArray;
        }
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'name',
            1 => 'type',
            2 => 'session',
        ];
        $sortColumn = $columns[$request->input('order.0.column')];
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';
        foreach ($leaves as $leave) {
            $dataArray[] = [
                'name' => $leave->users->full_name,
                'type' => $leave->type,
                'session' => $leave->session,
            ];
        }
        $dataArray = collect($dataArray)->$sort($sortColumn)
                                        ->skip($offset)
                                        ->take($limit);

        return $dataArray;
    }

    public function formatOnLeaveUserList($leaves)
    {
        $dataArray = [];
        if (! $leaves) {
            return $dataArray;
        }
        foreach ($leaves as $leave) {
            $dataArray[] = [
                'name' => $leave['name'],
                'type' => $leave['type'],
                'session' => $leave['session'],
            ];
        }

        return $dataArray;
    }

    public function setOverdueProjectsList($projects, $request)
    {
        $dataArray = [];
        if (! $projects) {
            return $dataArray;
        }
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'projectName',
            1 => 'projectClientName',
            3 => 'projectEndDate',
        ];
        $sortColumn = $columns[$request->input('order.0.column')];
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';

        foreach ($projects as $project) {
            $dataArray[] = [
                'projectId' => $project->id,
                'projectName' => $project->project_name,
                'projectClientName' => $project->client->company_name,
                'projectUsers' => $project->projectUsers,
                'projectEndDate' => $project->end_date_format
            ];
        }
        $dataArray = collect($dataArray)->$sort($sortColumn)
                                        ->skip($offset)
                                        ->take($limit);

        return $dataArray;
    }

    public function formatOverdueProjectsList($projects)
    {
        $dataArray = [];
        if (! $projects) {
            return $dataArray;
        }
        foreach ($projects as $project) {
            $projectUsers = '';
            foreach ($project['projectUsers'] as $user) {
                $projectUsers .= $user->full_name.',';
            }
            $projectUsers = rtrim($projectUsers, ',');
            $dataArray[] = [
                'projectName' => '<a href='.'/projects/'.$project['projectId'].'>'.$project['projectName'].'</a>',
                'projectClientName' => $project['projectClientName'],
                'projectUsers' => $projectUsers,
                'projectEndDate' => $project['projectEndDate'],
            ];
        }

        return $dataArray;
    }

    public function setOverdueTasksList($tasks, $request)
    {
        $dataArray = [];
        if (! $tasks) {
            return $dataArray;
        }
        $offset = $request->get('start') ?? 0;
        $limit = $request->get('length') ?? 25;
        $columns = [
            0 => 'taskTitle',
            1 => 'taskProjectName',
            3 => 'taskEndDate',
        ];
        $sortColumn = $columns[$request->input('order.0.column')];
        $sort = $request->input('order.0.dir') == 'desc' ? 'sortByDesc' : 'sortBy';

        foreach ($tasks as $task) {
            $dataArray[] = [
                'taskId' => $task->id,
                'taskTitle' => $task->title,
                'taskProjectName' => $task->project->project_name,
                'taskUsers' => $task->users,
                'taskEndDate' => $task->end_date_format
            ];
        }
        $dataArray = collect($dataArray)->$sort($sortColumn)
                                        ->skip($offset)
                                        ->take($limit);

        return $dataArray;
    }

    public function formatOverdueTasksList($tasks)
    {
        $dataArray = [];
        if (! $tasks) {
            return $dataArray;
        }
        foreach ($tasks as $task) {
            $taskUsers = '';
            foreach ($task['taskUsers'] as $user) {
                $taskUsers .= $user->full_name.',';
            }
            $taskUsers = rtrim($taskUsers, ',');
            $dataArray[] = [
                'taskTitle' => '<a href='.'/tasks/'.$task['taskId'].'>'.$task['taskTitle'].'</a>',
                'taskProjectName' => $task['taskProjectName'],
                'taskUsers' => $taskUsers,
                'taskEndDate' => $task['taskEndDate'],
            ];
        }

        return $dataArray;
    }

    public function getStartDateForFinYearData($settings, $year)
    {
        return DateTime::createFromFormat('d/m/Y', $settings['start']['daymonth'].'/'.$year);
    }

    public function getEndDateForFinYearData($settings, $year)
    {
        return DateTime::createFromFormat('d/m/Y', $settings['end']['daymonth'].'/'.$year);
    }

    public function getInterval()
    {
        return DateInterval::createFromDateString('1 month');
    }

    public function getFinYearData($settings, $year)
    {
        $startDate = $this->getStartDateForFinYearData($settings, $year);
        $endDate = $this->getEndDateForFinYearData($settings, $year);

        if ($endDate < $startDate) {
            $endDate->modify('+1 year');
            $data['nextYear'] = 1;
        }

        $interval = $this->getInterval();
        $period = new DatePeriod($startDate, $interval, $endDate);
        $months = [];
        $monthsNumber = [];
        foreach ($period as $date) {
            $monthName = $date->format('M');
            $yearName = $date->format('Y');
            $months[] = $monthName.'-'.$yearName;
            $monthsNumber[] = $yearName.'-'.$date->format('m');
        }
        if (isset($data['nextYear']) && ($settings['start']['month'] == $settings['end']['month'])) {
            $months[] = $endDate->format('M').'-'.$endDate->format('Y');
            $monthsNumber[] = $endDate->format('Y').'-'.$endDate->format('m');
        }

        $data['months'] = $months;
        $data['monthsNumber'] = $monthsNumber;
        $data['startDate'] = $startDate->format('Y-m-d');
        $data['endDate'] = $endDate->format('Y-m-d');

        return $data;
    }

    public function getStartYear($settings, $year)
    {
        if ($year != null) {
            return $year;
        }
        $startDate = $this->getStartDate($settings);
        $endDate = $this->getEndDate($settings);

        if ($endDate < $startDate) {
            $endDate->modify('+1 year');
        }
        if (Carbon::now()->between($startDate, $endDate)) {
            return $this->getYear();
        } else {
            return $this->getYear() - 1;
        }
    }

    public function getStartDate($settings)
    {
        return DateTime::createFromFormat('d/m/Y', $settings['start']['daymonth'].'/'.date('Y'));
    }

    public function getEndDate($settings)
    {
        return DateTime::createFromFormat('d/m/Y', $settings['end']['daymonth'].'/'.date('Y'));
    }

    public function getCountDetails()
    {
        $tasks = $this->dashboardRepository->getTasksForCount();

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
        return $this->dashboardRepository->getTotalHours();
    }

    public function getProjects($currentUserId)
    {
        return $this->dashboardRepository->getProjects($currentUserId);
    }

    public function getInProgressTasksIsClient($clientProjects)
    {
        return $this->dashboardRepository->getInProgressTasksIsClient($clientProjects);
    }

    public function getInProgressTasksNotClient($clientProjects)
    {
        return $this->dashboardRepository->getInProgressTasksNotClient($clientProjects);
    }

    public function getRejectedTasksIsClient($clientProjects)
    {
        return $this->dashboardRepository->getRejectedTasksIsClient($clientProjects);
    }

    public function getRejectedTasksNotClient()
    {
        return $this->dashboardRepository->getRejectedTasksNotClient();
    }

    public function updateRejectedTasks($rejected)
    {
        $qaIssues = $this->getAllQaIssues();
        foreach ($rejected as $key => $rejectedTask) {
            $qaIssuesList = $rejectedTask->reason;
            $issueList = explode('_', $qaIssuesList);
            $rejectReasons = [];
            foreach ($qaIssues as $issue) {
                if (in_array($issue->id, $issueList)) {
                    $rejectReasons[] = $issue->title;
                }
            }
            $rejected[$key]['issue'] = $rejectReasons;
        }

        return $rejected;
    }

    public function getThisWeek()
    {
        $tasks = $this->dashboardRepository->getTaskSessionsForThisWeek();
        $total = ['Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0, 'total' => 0];

        foreach ($tasks as $task) {
            $day = date('D', strtotime($task->created_at));
            $total[$day] += (int) $task->total;
            $total['total'] += (int) $task->total;
        }

        return $total;
    }

    public function getGeneralForChartData($startDate, $endDate)
    {
        return $this->dashboardRepository->getGeneralForChartData($startDate, $endDate);
    }

    public function getOverheadsForChartData($startDate, $endDate)
    {
        return $this->dashboardRepository->getOverheadsForChartData($startDate, $endDate);
    }

    public function getPayrollForChartData($startDate, $endDate)
    {
        return $this->dashboardRepository->getPayrollForChartData($startDate, $endDate);
    }

    public function getIncomeForChartData($month, $invoice, $proforma, $creditValue)
    {
        return round(((isset($invoice[$month]) ? $invoice[$month]->amount : 0) +
                    (isset($proforma[$month]) ? $proforma[$month]->amount : 0)) -
                    (isset($creditValue[$month]) ? $creditValue[$month]->amount : 0), 2);
    }

    public function getExpenseForChartData($base, $month, $general, $overheads, $payroll)
    {
        return floor($base * ((isset($general[$month]) ? $general[$month]->expense : 0) +
                    (isset($overheads[$month]) ? $overheads[$month]->expense : 0) +
                    (isset($payroll[$month]) ? $payroll[$month]->expense : 0)));
    }
}
