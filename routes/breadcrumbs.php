<?php

use App\Models\Asset;
use App\Models\EmployeeHikeHistory;
use App\Models\IssueRecord;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('Report', function ($trail) {
    $trail->push('Report', '');
});

Breadcrumbs::for('Tools', function ($trail) {
    $trail->push('Tools', '');
});

Breadcrumbs::for('Timesheets', function ($trail) {
    $trail->push('Timesheets', '');
});

Breadcrumbs::for('Checklists', function ($trail) {
    $trail->push('Checklists', '');
});

Breadcrumbs::for('Tasks', function ($trail) {
    $trail->push('Tasks', '');
});

Breadcrumbs::for('Projects', function ($trail) {
    $trail->push('Projects', '');
});

Breadcrumbs::for('Payroll', function ($trail) {
    $trail->push('Payroll', '');
});

Breadcrumbs::for('Users', function ($trail) {
    $trail->push('Users', '');
});

Breadcrumbs::for('Miscellaneous', function ($trail) {
    $trail->push('Miscellaneous', '');
});

Breadcrumbs::for('Leaves', function ($trail) {
    $trail->push('Leaves', '');
});

Breadcrumbs::for('Settings', function ($trail) {
    $trail->push('Settings', '');
});

Breadcrumbs::for('dashboards', function ($trail) {
    $trail->push('Dashboard', route('dashboards'));
});

Breadcrumbs::for('userStatusList', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('User List', route('userStatusList'));
});

Breadcrumbs::for('updateDailyStatusReportView', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Update Daily Status Report', route('updateDailyStatusReportView'));
});

Breadcrumbs::for('clients.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Clients', route('clients.index'));
});

Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Users', route('users.index'));
});

Breadcrumbs::for('projects.index', function ($trail) {
    $trail->parent('Projects');
    $trail->push('Active Projects', route('projects.index'));
});

Breadcrumbs::for('archivedProjects', function ($trail) {
    $trail->parent('Projects');
    $trail->push('Archived Projects', route('archivedProjects'));
});

Breadcrumbs::for('archivedProjectSearch', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Archived Projects', route('archivedProjectSearch'));
});

Breadcrumbs::for('newsletters.index', function ($trail) {
    $trail->parent('Miscellaneous');
    $trail->push('Newsletters', route('projects.index'));
});

Breadcrumbs::for('projects.update', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('project_search', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('project-credentials.show', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Credentials', route('project-credentials.show', $project));
});

Breadcrumbs::for('project-documents.show', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Documents', route('project-documents.show', $project));
});

Breadcrumbs::for('projects.show', function ($trail, $project) {
    $trail->parent('projects.index');
    $trail->push(project::find($project)->project_name, route('projects.show', $project));
});

Breadcrumbs::for('viewAgile', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Agile Board', route('viewAgile', $project));
});

Breadcrumbs::for('searchAgile', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Agile Board', route('searchAgile', $project));
});

Breadcrumbs::for('changePassword', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Change Password', route('changePassword'));
});

Breadcrumbs::for('changePasswordAction', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Change Password', route('changePasswordAction'));
});

Breadcrumbs::for('tasks.index', function ($trail) {
    $trail->parent('Tasks');
    $trail->push('Active Tasks', route('tasks.index'));
});

Breadcrumbs::for('ongoing-tasks.index', function ($trail) {
    $trail->parent('Tasks');
    $trail->push('Ongoing Tasks', route('ongoing-tasks.index'));
});

Breadcrumbs::for('ongoing-tasks.show', function ($trail, $tasks) {
    $trail->parent('ongoing-tasks.index');
    $trail->push('Details', route('ongoing-tasks.show', $tasks));
});

Breadcrumbs::for('upcomingTasks', function ($trail) {
    $trail->parent('Tasks');
    $trail->push('Upcoming Tasks', route('upcomingTasks'));
});

Breadcrumbs::for('completedTasks', function ($trail) {
    $trail->parent('Tasks');
    $trail->push('Completed Tasks', route('completedTasks'));
});

Breadcrumbs::for('taskSearch', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Tasks', route('taskSearch'));
});

Breadcrumbs::for('archivedTasks', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Archived Tasks', route('archivedTasks'));
});

Breadcrumbs::for('archivedTaskSearch', function ($trail) {
    $trail->parent('Tasks');
    $trail->push('Archived Tasks', route('archivedTaskSearch'));
});

Breadcrumbs::for('my-timesheet.index', function ($trail) {
    $trail->parent('Timesheets');
    $trail->push('My Timesheet', route('my-timesheet.index'));
});

Breadcrumbs::for('my-team-timesheet.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('My Team', route('my-team-timesheet.index'));
});

Breadcrumbs::for('team-timesheet-search', function ($trail) {
    $trail->push('Team timesheet', route('team-timesheet-search'));
});

Breadcrumbs::for('team.destroy', function ($trail, $teamId) {
    $trail->push('Team delete', route('team.destroy', ['id' => $teamId]));
});

Breadcrumbs::for('my-credentials.index', function ($trail) {
    $trail->parent('Tools');
    $trail->push('Credentials', route('my-credentials.index'));
});

Breadcrumbs::for('assignLeave', function ($trail) {
    $trail->parent('Leaves');
    $trail->push('Assign Leave', route('assignLeave'));
});

Breadcrumbs::for('taskBounce', function ($trail) {
    $trail->parent('Report');
    $trail->push('Task Bounce List', route('taskBounce'));
});

Breadcrumbs::for('taskBounceReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Task Bounce Report', route('taskBounceReport'));
});

Breadcrumbs::for('taskBounceGraph', function ($trail) {
    $trail->parent('Report');
    $trail->push('Task Bounce Graph', route('taskBounceGraph'));
});

Breadcrumbs::for('dailyStatusReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Daily Status Report', route('dailyStatusReport'));
});

Breadcrumbs::for('taskTimeReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Task Time Report', route('taskTimeReport'));
});

Breadcrumbs::for('previousApplications', function ($trail) {
    $trail->parent('Leaves');
    $trail->push('Leave Applications', route('previousApplications'));
});

Breadcrumbs::for('tasks.show', function ($trail, $tasks) {
    if (app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'tasks.show') {
        $trail->parent('tasks.index');
    } else {
        if (! in_array(app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName(), ['projects.show', 'project-credentials.show', 'project-documents.show', 'viewAgile', 'searchAgile'])) {
            $trail->parent(app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName());
        } else {
            $trail->parent('tasks.index');
        }
    }
    $task = Task::find($tasks);
    if ($task) {
        $trail->push($task->title, route('tasks.show', $tasks));
    } else {
        $trail->push('Task Not Found');
    }
});

Breadcrumbs::for('projectReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Project Report', route('projectReport'));
});

Breadcrumbs::for('taskReport', function ($trail, $project) {
    $trail->parent('projectReport');
    $trail->push('Tasks', route('taskReport', $project));
});

Breadcrumbs::for('userReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('User Report', route('userReport'));
});

Breadcrumbs::for('performanceReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Employee Performance', route('performanceReport'));
});

Breadcrumbs::for('clientReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Client Report', route('clientReport'));
});

Breadcrumbs::for('totalCostReports', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Total Cost', route('totalCostReports'));
});

Breadcrumbs::for('projectBillabilityReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('Billability Report', route('projectBillabilityReport'));
});

Breadcrumbs::for('projectBillabilityHoursGraph', function ($trail) {
    $trail->parent('projectBillabilityReport');
    $trail->push('Progress Graph', route('projectBillabilityHoursGraph'));
});

Breadcrumbs::for('company-info', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Company Information', route('company-info'));
});
Breadcrumbs::for('project-technologies.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Project Technologies', route('project-technologies.index'));
});
Breadcrumbs::for('overheads.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Overheads', route('overheads.index'));
});
Breadcrumbs::for('overheads.store', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overheads', route('overheads.index'));
});
Breadcrumbs::for('overheads.destroy', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overheads', route('overheads.index'));
});
Breadcrumbs::for('overheads.update', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overheads', route('overheads.index'));
});

Breadcrumbs::for('overdueTasks', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overdue Tasks', route('overdueTasks'));
});
Breadcrumbs::for('employeeDashboard', function ($trail) {
    $trail->push('Dashboards', route('employeeDashboard'));
});

Breadcrumbs::for('userTimesheetSearch', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Timesheet', route('userTimesheetSearch'));
});

Breadcrumbs::for('viewSheetUser', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('User Timesheet', route('viewSheetUser'));
});

Breadcrumbs::for('viewSheetClient', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Client Timesheet', route('viewSheetClient'));
});

Breadcrumbs::for('viewSheetProject', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Project Timesheet', route('viewSheetProject'));
});

Breadcrumbs::for('searchBillableHours', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Billable Hours', route('searchBillableHours'));
});

Breadcrumbs::for('fixed-overhead.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Manage Fixed Expenses', route('fixed-overhead.index'));
});

Breadcrumbs::for('clientMonthly', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Clients Monthly Sheet', route('clientMonthly'));
});

Breadcrumbs::for('userMonthly', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Users Monthly Sheet', route('userMonthly'));
});

Breadcrumbs::for('userDaterange', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Users Daterange Sheet', route('userDaterange'));
});

Breadcrumbs::for('projectDaterange', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Projects Daterange Sheet', route('projectDaterange'));
});

Breadcrumbs::for('clientDaterange', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Clients Daterange Sheet', route('clientDaterange'));
});

Breadcrumbs::for('manage-holidays.index', function ($trail) {
    $trail->parent('Miscellaneous');
    $trail->push('Holidays', route('manage-holidays.index'));
});

Breadcrumbs::for('apply-leave.index', function ($trail) {
    $trail->parent('Leaves');
    $trail->push('Leave Application', route('apply-leave.index'));
});

Breadcrumbs::for('adminIndex', function ($trail) {
    $trail->parent('Leaves');
    $trail->push('Leave Management', route('adminIndex'));
});

Breadcrumbs::for('showProfile', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('My Profile', route('showProfile'));
});

Breadcrumbs::for('errors.404', function ($trail) {
    $trail->push('Error');
});

Breadcrumbs::for('user-access-levels', function ($trail) {
    $trail->parent('Settings');
    $trail->push('User Access Levels', route('user-access-levels'));
});

Breadcrumbs::for('issue-records.index', function ($trail) {
    $trail->parent('Tools');
    $trail->push('Issue Records', route('issue-records.index'));
});

Breadcrumbs::for('issue-records.show', function ($trail, $issue) {
    $trail->parent('issue-records.index');
    $trail->push(IssueRecord::find($issue)->title, route('issue-records.show', $issue));
});

Breadcrumbs::for('work-notes.index', function ($trail) {
    $trail->parent('Tools');
    $trail->push('Work Notes', route('work-notes.index'));
});

Breadcrumbs::for('qa-feedback.index', function ($trail) {
    $trail->parent('Tools');
    $trail->push('QA Feedback', route('qa-feedback.index'));
});

Breadcrumbs::for('session-types.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Manage Session Types', route('session-types.index'));
});

Breadcrumbs::for('status-types.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Manage Status Types', route('status-types.index'));
});

Breadcrumbs::for('santa-members.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Manage Santa Members', route('santa-members.index'));
});

Breadcrumbs::for('santa-members.setSanta', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Set Santa Members', route('santa-members.setSanta'));
});

Breadcrumbs::for('santa-members.viewSanta', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('View Santa Members', route('santa-members.viewSanta'));
});

Breadcrumbs::for('find-my-santa', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('View My Santa', route('find-my-santa'));
});

Breadcrumbs::for('checklists.index', function ($trail) {
    $trail->parent('Checklists');
    $trail->push('Manage Checklists', route('checklists.index'));
});

Breadcrumbs::for('employeeChecklist', function ($trail) {
    $trail->parent('checklists.index');
    $trail->push('Employee Checklists', route('employeeChecklist'));
});

Breadcrumbs::for('useChecklists', function ($trail) {
    $trail->parent('Checklists');
    $trail->push('Checklists', route('useChecklists'));
});

Breadcrumbs::for('checklistReport', function ($trail) {
    $trail->parent('Checklists');
    $trail->push('Checklist Report', route('checklistReport'));
});

Breadcrumbs::for('easyAccess', function ($trail) {
    $trail->parent('Tools');
    $trail->push('Manage Easy Access', route('easyAccess'));
});

Breadcrumbs::for('alerts.index', function ($trail) {
    $trail->parent('Miscellaneous');
    $trail->push('Manage Popup Alerts', route('alerts.index'));
});

Breadcrumbs::for('guidelines.index', function ($trail) {
    $trail->parent('Tools');
    $trail->push('Manage Guidelines', route('guidelines.index'));
});

Breadcrumbs::for('guidelines.show', function ($trail, $item) {
    $trail->parent('guidelines.index');
    $trail->push('Content', route('guidelines.show', $item));
});

Breadcrumbs::for('recruitments.index', function ($trail) {
    $trail->parent('Miscellaneous');
    $trail->push('Manage Recruitments', route('recruitments.index'));
});

Breadcrumbs::for('searchCandidate', function ($trail) {
    $trail->push('Search Candidates', route('searchCandidate'));
});

Breadcrumbs::for('listSchedules', function ($trail) {
    $trail->parent('Miscellaneous');
    $trail->push('Recruitment Schedules', route('listSchedules'));
});

Breadcrumbs::for('manage-payment-methods.index', function ($trail) {
    $trail->parent('invoicing.index');
    $trail->push('Manage Payment Methods', route('manage-payment-methods.index'));
});

Breadcrumbs::for('compensations.index', function ($trail) {
    $trail->parent('Leaves');
    $trail->push('Compensatory Work', route('compensations.index'));
});

Breadcrumbs::for('compensatoryApplications', function ($trail) {
    $trail->parent('Leaves');
    $trail->push('Compensatory Application', route('compensatoryApplications'));
});

Breadcrumbs::for('base-currency.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Base Currency', route('base-currency.index'));
});

Breadcrumbs::for('createCreditNote', function ($trail, $item) {
    $trail->parent('invoicing.index');
    $trail->push('Credit Notes', route('createCreditNote', $item));
});
Breadcrumbs::for('showCreditNote', function ($trail, $item) {
    $trail->parent('invoicing.index');
    $trail->push('Credit Notes', route('showCreditNote', $item));
});

Breadcrumbs::for('credentials.index', function ($trail) {
    $trail->parent('Tools');
    $trail->push('Project Credentials', route('credentials.index'));
});

Breadcrumbs::for('credentials.show', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Project Credentials', route('credentials.index'));
});

Breadcrumbs::for('newTimeSheet', function ($trail) {
    $trail->parent('Timesheets');
    $trail->push('Timesheets', route('newTimeSheet'));
});

Breadcrumbs::for('salary-component.index', function ($trail) {
    $trail->parent('Payroll');
    $trail->push('Salary Component', route('salary-component.index'));
});

Breadcrumbs::for('salary-component.destroy', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Salary Component', route('salary-component.index'));
});

Breadcrumbs::for('payroll.index', function ($trail) {
    $trail->parent('Payroll');
    $trail->push('Manage Payroll', route('payroll.index'));
});

Breadcrumbs::for('payroll.show', function ($trail) {
    $trail->parent('payroll.index');
    $trail->push('Employee Payroll', route('payroll.index'));
});

Breadcrumbs::for('payroll-user.update', function ($trail) {
    $trail->parent('payroll.index');
    $trail->push('Employee Payroll', route('payroll.index'));
});

Breadcrumbs::for('payroll.filter', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Payroll', route('payroll.index'));
});

Breadcrumbs::for('payroll-filter.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Payroll', route('payroll.index'));
});

Breadcrumbs::for('payroll-user.view', function ($trail) {
    $trail->parent('payroll.index');
    $trail->push('Employee Payroll', route('payroll.index'));
});

Breadcrumbs::for('payroll-user.index', function ($trail) {
    $trail->parent('payroll.index');
    $trail->push('Employee Payroll', route('payroll.index'));
});

Breadcrumbs::for('reports.index', function ($trail) {
    $trail->parent('Settings');
    $trail->push('Reports', route('reports.index'));
});

Breadcrumbs::for('payslip.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Payslip', route('payslip.index'));
});

Breadcrumbs::for('payslip.show', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Payslip', route('payslip.index'));
});

Breadcrumbs::for('expenses.store', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overheads', route('overheads.index'));
});

Breadcrumbs::for('expenses.update', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overheads', route('overheads.index'));
});

Breadcrumbs::for('expenses.destroy', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Overheads', route('overheads.index'));
});
Breadcrumbs::for('accountSettings', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Account Settings', route('accountSettings'));
});

Breadcrumbs::for('userAccount', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('User Account Report', route('userAccount'));
});

Breadcrumbs::for('userLeaveReport', function ($trail) {
    $trail->parent('Report');
    $trail->push('User Leave Report', route('userLeaveReport'));
});

Breadcrumbs::for('PercentageSplitUp', function ($trail) {
    $trail->parent('Report');
    $trail->push('Percentage Split Up', route('PercentageSplitUp'));
});

Breadcrumbs::for('PercentageSplitUpPerClient', function ($trail) {
    $trail->parent('Report');
    $trail->push('Percentage Split Up Per Client', route('PercentageSplitUpPerClient'));
});

Breadcrumbs::for('PercentageSplitUpPerCountry', function ($trail) {
    $trail->parent('Report');
    $trail->push('Percentage Split Up Per Country', route('PercentageSplitUpPerCountry'));
});

Breadcrumbs::for('ProjectCost', function ($trail) {
    $trail->parent('Report');
    $trail->push('Project Cost', route('ProjectCost'));
});

Breadcrumbs::for('projectCostDetail', function ($trail, $project) {
    $trail->parent('Report');
    $trail->push('Project Cost', route('ProjectCost'));
    $trail->push(Project::find($project)->project_name, route('projectCostDetail', $project));
});

Breadcrumbs::for('salary-hike.index', function ($trail) {
    $trail->push('Salary Hike', route('salary-hike.index'));
});

Breadcrumbs::for('salary-hike-search', function ($trail) {
    $trail->push('Salary Hike', route('salary-hike-search'));
});

Breadcrumbs::for('salary-hike.show', function ($trail, $item) {
    $trail->push('Salary Hike', route('salary-hike.index'));
    $trail->push(EmployeeHikeHistory::find($item)->id, route('salary-hike.show', $item));
});

Breadcrumbs::for('employeeHikeHistory', function ($trail, $item) {
    $trail->push('Salary Hike', route('salary-hike.index'));
    $trail->push('Employee');
    $trail->push(User::find($item)->id, route('employeeHikeHistory', $item));
});

Breadcrumbs::for('project.index', function ($trail) {
    $trail->parent('dashboards');
    $trail->push('Projects', 'project.index');
});

Breadcrumbs::for('Assets', function ($trail) {
    $trail->push('Assets', '');
});

Breadcrumbs::for('assets.index', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Assets', route('assets.index'));
});

Breadcrumbs::for('assets.employeeAssetList', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Assets', route('assets.employeeAssetList'));
});

Breadcrumbs::for('assets.employeeTicketRaisedAssetList', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Tickets', route('assets.employeeTicketRaisedAssetList'));
});

Breadcrumbs::for('assets.issueUpdate', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Ticket Update', route('assets.issueUpdate'));
});

Breadcrumbs::for('assets.ticketRaisedAssetList', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Tickets', route('assets.ticketRaisedAssetList'));
});

Breadcrumbs::for('assets.show', function ($trail, $asset) {
    $trail->parent('assets.index');
    $trail->push(Asset::find($asset)->name, route('assets.show', $asset));
});

Breadcrumbs::for('assets.asset-search', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Tickets', route('assets.asset-search'));
});

Breadcrumbs::for('assets.ticket-raised-asset-search', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Assets', route('assets.ticket-raised-asset-search'));
});

Breadcrumbs::for('assets.employee-ticket-raised-asset-search', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Tickets', route('assets.employee-ticket-raised-asset-search'));
});

Breadcrumbs::for('asset-types.index', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Types', route('asset-types.index'));
});

Breadcrumbs::for('asset-vendors.index', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Vendors', route('asset-vendors.index'));
});

Breadcrumbs::for('ticket-status.index', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Ticket Status', route('ticket-status.index'));
});
Breadcrumbs::for('attributes.index', function ($trail) {
    $trail->parent('Assets');
    $trail->push('Manage Attributes', route('attributes.index'));
});
