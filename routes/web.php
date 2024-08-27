<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group(['middleware' => 'web'], function () {
    Route::post('/login', 'Auth\LoginController@login');
    Route::view('/login', 'layout.login')->name('login');
    Route::post('/verify', 'Auth\LoginController@verifyEmail')->name('email-verify');
    Route::get('change-password', 'Auth\AccountSettings@resetPassword')->name('changePassword');
    Route::get('/get-leave/{id}', 'Leaves\Apply\ApplyLeaveController@getLeave')->name('getLeave');
    Route::get('/add-task-session', 'TaskSessionController@addTaskSession')->name('storeTaskSession');

    Route::group(['middleware' => ['auth', 'xss']], function () {
        Route::get('update-status-report', 'Reports\DailyStatusController@updateDailyStatusReportView')->name('updateDailyStatusReportView');
        Route::post('update-daily-status-report', 'Reports\DailyStatusController@updateDailyStatusReport')->name('dailyStatusReport.update');
        Route::post('change-password-action', 'HomeController@changePasswordAction')->name('changePasswordAction');
        Route::get('autofill-eod', 'Reports\DailyStatusController@autofillDailyStatusReport')->name('autofill-eod');
    });

    Route::group(['middleware' => ['auth', 'checkDSRStatus', 'xss']], function () {
        Route::group(['middleware' => ['permission:view-dashboard']], function () {
            Route::get('/dashboard', 'Dashboard\DashboardController@index')->name('dashboard');
            Route::get('/get-productive-users', 'Dashboard\EmployeeStatusController@getProductiveUsers')->name('getProductiveUsers');
            Route::get('/get-upskilling-users', 'Dashboard\EmployeeStatusController@getUpSkillingUsers')->name('getUpSkillingUsers');
            Route::get('/get-idle-users', 'Dashboard\EmployeeStatusController@getIdleUsers')->name('getIdleUsers');
            Route::get('/get-onleave-users', 'Dashboard\EmployeeStatusController@getOnLeaveUsers')->name('getOnLeaveUsers');
            Route::get('/get-overdue-projects', 'Dashboard\OverDueController@getOverdueProjects')->name('getOverdueProjects');
            Route::get('/get-overdue-tasks', 'Dashboard\OverDueController@getOverdueTasks')->name('getOverdueTasks');
            Route::post('add-easy-access', 'Tools\EasyAccessController@addEasyAccess')->name('addEasyAccess');
            Route::post('delete-easy-access', 'Tools\EasyAccessController@deleteEasyAccess')->name('deleteEasyAccess');
            Route::post('edit-easy-access', 'Tools\EasyAccessController@editEasyAccess')->name('editEasyAccess');
            Route::get('easy-access', 'Tools\EasyAccessController@easyAccess')->name('easyAccess');
            Route::post('load-chart-dashboard', 'Dashboard\EmployeeStatusController@setChartData')->name('setChartData');
            Route::post('get-pie-chart-data', 'Dashboard\EmployeeStatusController@getPieChartData')->name('getPieChartData');
        });

        Route::group(['middleware' => ['permission:view-user-access-levels']], function () {
            Route::get('user-access-levels', 'Settings\UserAccessLevelsController@index')->name('user-access-levels');
        });

        Route::group(['middleware' => ['permission:manage-user-access-levels']], function () {
            Route::post('access-level-add-role', 'Settings\UserAccessLevelsController@addRole')->name('access-level-add-role');
            Route::post('access-level-add-permission', 'Settings\UserAccessLevelsController@addPermission')->name('access-level-add-permission');
            Route::post('access-level-store', 'Settings\UserAccessLevelsController@store')->name('access-level-store');
            Route::post('access-level-role-delete', 'Settings\UserAccessLevelsController@delete')->name('access-level-role-delete');
        });

        Route::group(['middleware' => ['permission:view-overheads']], function () {
            Route::resource('overheads', 'Settings\OverHeadsAndExpenses\ManageExpensesController');
            Route::post('load-chart', 'Settings\OverHeadsAndExpenses\ManageExpensesController@loadChart')->name('loadChart');
            Route::post('load-pie-expense', 'Settings\OverHeadsAndExpenses\FixedOverHeadsController@loadChartExpense')->name('loadChartExpense');
            Route::post('list-table', 'Settings\OverHeadsAndExpenses\ManageExpensesController@listTable')->name('listTable');
            Route::get('get-types', 'Settings\OverHeadsAndExpenses\ManageExpensesController@getTypes')->name('getTypes');
            Route::get('manage-overhead', 'OverheadController@manageOverhead')->name('manageOverhead');
            Route::resource('expenses', 'Settings\OverHeadsAndExpenses\ExpenseController');
            Route::get('get-expense-types', 'Settings\OverHeadsAndExpenses\ExpenseController@getExpenseTypes')->name('getExpenseTypes');
            Route::post('list-expense-table', 'Settings\OverHeadsAndExpenses\ExpenseController@listTable')->name('listExpenseTable');
            Route::post('load-pie-expenses', 'Settings\OverHeadsAndExpenses\ExpenseController@loadPieExpense')->name('loadPieExpense');
        });

        Route::group(['middleware' => ['permission:view-holidays']], function () {
            Route::resource('manage-holidays', 'Miscellaneous\HolidayController');
            Route::post('get-holiday-list', 'Miscellaneous\HolidayController@getHolidayList')->name('getHolidayList');
            Route::post('manage-weeklyholidays', 'Miscellaneous\HolidayController@manageWeeklyHolidays')->name('manageWeeklyHolidays');
            Route::get('export-holiday/{year}', 'Miscellaneous\HolidayController@exportHolidays')->name('holiday.export');
        });

        Route::group(['middleware' => ['permission:view-projects']], function () {
            Route::get('/projects', 'Projects\ActiveController@index')->name('projects.index');
            Route::get('/projects/{id}', 'Projects\ActiveController@show')->name('projects.show');
            Route::any('project-search', 'Projects\ActiveController@searchProject')->name('project_search');
            Route::post('update-project-detail', 'Projects\ActiveController@updateProjectDetails')->name('updateProjectDetails');
            Route::get('archived-projects', 'Projects\ArchiveController@archivedProjects')->name('archivedProjects');
            Route::any('archived-project-search', 'Projects\ArchiveController@archivedProjectSearch')->name('archivedProjectSearch');
            Route::post('change-archive-project', 'Projects\ArchiveController@changeArchiveProject')->name('changeArchiveProject');
        });

        Route::group(['middleware' => ['permission:manage-projects']], function () {
            Route::post('/projects', 'Projects\ActiveController@store')->name('projects.store');
            Route::get('/projects/{id}/edit', 'Projects\ActiveController@edit')->name('projects.edit');
            Route::patch('/projects/{id}', 'Projects\ActiveController@update')->name('projects.update');
            Route::delete('/projects/{id}', 'Projects\ActiveController@destroy')->name('projects.delete');
            Route::post('add-project-managers', 'Projects\ActiveController@addProjectManagersAjax');
        });

        Route::group(['middleware' => ['permission:view-project-credentials']], function () {
            Route::get('/project-credentials', 'Tools\ProjectCredentialsController@index')->name('project-credentials.index');
            Route::get('/project-credentials/{id}', 'Tools\ProjectCredentialsController@show')->name('project-credentials.show');
        });

        Route::group(['middleware' => ['permission:manage-project-credentials']], function () {
            Route::post('/project-credentials', 'Tools\ProjectCredentialsController@store')->name('project-credentials.store');
            Route::get('/project-credentials/{id}/edit', 'Tools\ProjectCredentialsController@edit')->name('project-credentials.edit');
            Route::patch('/project-credentials/{id}', 'Tools\ProjectCredentialsController@update')->name('project-credentials.update');
            Route::delete('/project-credentials/{id}', 'Tools\ProjectCredentialsController@destroy')->name('project-credentials.delete');
        });

        Route::group(['middleware' => ['permission:view-project-documents']], function () {
            Route::get('/project-documents', 'Projects\ProjectDocumentsController@index')->name('project-documents.index');
            Route::get('/project-documents/{id}', 'Projects\ProjectDocumentsController@show')->name('project-documents.show');
        });

        Route::group(['middleware' => ['permission:manage-project-documents']], function () {
            Route::post('/project-documents', 'Projects\ProjectDocumentsController@store')->name('project-documents.store');
            Route::get('/project-documents/{id}/edit', 'Projects\ProjectDocumentsController@edit')->name('project-documents.edit');
            Route::patch('/project-documents/{id}', 'Projects\ProjectDocumentsController@update')->name('project-documents.update');
            Route::delete('/project-documents/{id}', 'Projects\ProjectDocumentsController@destroy')->name('project-documents.delete');
        });

        Route::group(['middleware' => ['permission:view-fixed-overheads']], function () {
            Route::resource('fixed-overhead', 'Settings\OverHeadsAndExpenses\FixedOverHeadsController');
            Route::post('load-pie', 'Settings\OverHeadsAndExpenses\FixedOverHeadsController@loadPie')->name('loadPie');
            Route::post('add-to-month', 'Settings\OverHeadsAndExpenses\FixedOverHeadsController@addToMonth')->name('addToMonth');
        });

        Route::group(['middleware' => ['permission:manage-alerts']], function () {
            Route::resource('alerts', 'Miscellaneous\ManagePopupAlertController');
        });

        Route::group(['middleware' => ['permission:manage-settings']], function () {
            Route::resource('base-currency', 'Settings\BaseCurrencyController');
            Route::post('change-currency', 'Settings\BaseCurrencyController@changeCurrency')->name('changeCurrency');
            Route::get('company-info', 'Settings\CompanyInfoController@edit')->name('company-info');
            Route::resource('project-technologies', 'Settings\ProjectTechnologiesController');
            Route::post('company-info/save', 'Settings\CompanyInfoController@store')->name('company-info.store');
            Route::get('reports-settings', 'Settings\ReportsSettingsController@index')->name('reports.index');
            Route::patch('reports-settings', 'Settings\ReportsSettingsController@update')->name('reports_settings.update');
            Route::get('projects-settings', 'Settings\ProjectSettingsController@index')->name('project.index');
            Route::patch('projects-settings', 'Settings\ProjectSettingsController@update')->name('projects_settings.update');
            Route::post('add-technologies', 'Settings\ProjectSettingsController@addTechnology')->name('add-technologies');
        });

        Route::group(['middleware' => ['permission:view-tasks']], function () {
            Route::get('/tasks', 'TaskController@index')->name('tasks.index');
            Route::any('task-search', 'TaskController@taskSearch')->name('taskSearch');
            Route::get('overdue-tasks', 'Reports\Dashboard\OverDueTasksController@showOverdueTasks')->name('overdueTasks');
            Route::post('overdue-tasks', 'Reports\Dashboard\OverDueTasksController@searchOverdueTasks')->name('searchOverdueTasks');
        });

        Route::group(['middleware' => ['permission:manage-tasks']], function () {
            Route::post('/tasks', 'TaskController@store')->name('tasks.store');
            Route::get('/tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
            Route::get('/tasks/delete-document/{id}', 'TaskController@deleteDoc')->name('tasks.deleteDoc');
            Route::patch('/tasks/{id}', 'TaskController@update')->name('tasks.update');
            Route::delete('/tasks/{id}', 'TaskController@destroy')->name('tasks.delete');
            Route::post('create-task-ajax', 'TaskController@createTaskAjax')->name('createTaskAjax');
            Route::patch('update-task-ajax', 'TaskController@updateTaskAjax')->name('updateTaskAjax');
            Route::post('delete-task-ajax', 'TaskController@deleteTaskAjax')->name('deleteTaskAjax');
            Route::post('destroy-task-ajax', 'TaskController@destroyTaskAjax')->name('destroyTaskAjax');
            Route::post('admin-task-approve', 'TaskController@adminApproveTask')->name('adminApproveTask');
            Route::post('update-progress', 'TaskController@updateProgress')->name('updateProgress');
            Route::post('create-sub-task', 'TaskController@createSubTask')->name('createSubTask');
            Route::post('/task-tags', 'TaskController@storeTag')->name('storeTaskTag');
            Route::post('get-sub-task-list', 'TaskController@getSubTaskList')->name('getSubTaskList');
            Route::post('get-assigness-list', 'TaskController@getAssigneesList')->name('getAssigneesList');
            Route::post('get-task-status', 'TaskController@updateTaskStatus')->name('updateTaskStatus');
            Route::resource('session-types', 'Settings\SessionTypeController');
            // Route::resource('status-types', 'TaskStatusTypeController');
            Route::get('archived-tasks', 'TaskController@archivedTasks')->name('archivedTasks');
            Route::any('archived-task-search', 'TaskController@archivedTaskSearch')->name('archivedTaskSearch');
            Route::post('change-archive', 'TaskController@changeArchive')->name('changeArchive');
            Route::get('get-project-tasks/{id}', 'TaskController@getProjectTasks');
        });

        Route::group(['middleware' => ['permission:view-users']], function () {
            Route::get('/users', 'Users\UserController@index')->name('users.index');
            Route::get('/users/{id}', 'Users\UserController@show')->name('users.show');
        });

        Route::group(['middleware' => ['permission:manage-users']], function () {
            Route::post('/users', 'Users\UserController@store')->name('users.store');
            Route::get('/users/{id}/edit', 'Users\UserController@edit')->name('users.edit');
            Route::patch('/users/{id}', 'Users\UserController@update')->name('users.update');
            Route::delete('/users/{id}', 'Users\UserController@destroy')->name('users.delete');
        });

        Route::group(['middleware' => ['permission:view-clients']], function () {
            Route::get('/clients', 'Users\ClientController@index')->name('clients.index');
            Route::get('/clients/{id}', 'Users\ClientController@show')->name('clients.show');
        });

        Route::group(['middleware' => ['permission:manage-clients']], function () {
            Route::post('/clients', 'Users\ClientController@store')->name('clients.store');
            Route::get('/clients/{id}/edit', 'Users\ClientController@edit')->name('clients.edit');
            Route::patch('/clients/{id}', 'Users\ClientController@update')->name('clients.update');
        });

        Route::group(['middleware' => ['permission:view-my-timesheet']], function () {
            Route::resource('my-timesheet', 'UserTimesheetController');
            Route::post('user-timesheet-search', 'UserTimesheetController@userTimesheetSearch')->name('userTimesheetSearch');
            Route::post('manage-entry', 'UserTimesheetController@manageEntry')->name('manageEntry');
            Route::resource('my-team-timesheet', 'TeamTimesheetController');
            Route::any('team-timesheet-search', 'TeamTimesheetController@searchTeamTimesheet')->name('team-timesheet-search');
            Route::get('show-team/{id}', 'TeamController@show')->name('showTeam');
            //Route::post('remove-team-member', 'TeamController@destroy')->name('removeTeamMember');
            Route::resource('team', 'TeamController')->except(['index', 'show']);
        });

        Route::group(['middleware' => ['permission:view-reports']], function () {
            Route::get('report/projects', 'Reports\ProjectController@projectReport')->name('projectReport');

            Route::post('projectSearch', 'Reports\ProjectController@projectSearch')->name('projectSearch');

            Route::get('task-report/{id}', 'Reports\ProjectController@taskReport')->name('taskReport');
            Route::get('report/users', 'Reports\UserController@userReport')->name('userReport');
            Route::post('userReportSearch', 'Reports\UserController@userReportSearch')->name('userReportSearch');

            Route::get('report/performance', 'Reports\PerformanceController@performanceReport')->name('performanceReport');
            Route::post('employeePerformanceSearch', 'Reports\PerformanceController@employeePerformanceSearch')->name('employeePerformanceSearch');
            Route::get('report/clients', 'Reports\ClientController@clientReport')->name('clientReport');

            Route::post('clientSearch', 'Reports\ClientController@clientSearch')->name('clientSearch');

            Route::get('archived-tasks', 'TaskController@archivedTasks')->name('archivedTasks');
            Route::post('change-archive', 'TaskController@changeArchive')->name('changeArchive');

            Route::get('task-bounce-list', 'Reports\TaskBounceController@index')->name('taskBounce');
            Route::post('task-bounce', 'Reports\TaskBounceController@taskBounceSearch')->name('taskBounceSearch');
            Route::post('task-bounce-report', 'Reports\TaskBounceController@bounceReportSearch')->name('bounceReportSearch');
            Route::get('task-bounce-report', 'Reports\TaskBounceController@report')->name('taskBounceReport');
            Route::get('task-bounce-graph', 'Reports\TaskBounceController@graph')->name('taskBounceGraph');
            Route::post('load-bounce-chart', 'Reports\TaskBounceController@setChartData')->name('setChartDataBounce');

            Route::get('report/task-time-report', 'Reports\TaskTimeController@index')->name('taskTimeReport');
            Route::post('/task-time', 'Reports\TaskTimeController@taskTimeSearch')->name('taskTimeSearch');
            Route::post('/get-task-time-users', 'Reports\TaskTimeController@getTaskTimeUsers')->name('getTaskTimeUsers');

            Route::match(['get', 'post'], 'report/project-billability-report', 'Reports\ProjectController@projectBillabilityReport')->name('projectBillabilityReport');
            Route::post('report/project-billability-report-project-filter', 'Reports\ProjectController@projectBillabilityReportAjaxProjectFilter')->name('projectBillabilityReportAjaxProjectFilter');
            Route::post('report/project-billability-graph', 'Reports\ProjectController@projectBillabilityGraph')->name('projectBillabilityGraph');
            Route::match(['get', 'post'], 'report/project-billability-hours-graph', 'Reports\ProjectController@projectBillabilityHoursGraph')->name('projectBillabilityHoursGraph');

            Route::post('report/save-filter', 'ReportFilterController@store')->name('saveReportFilter');
            Route::post('report/get-filter-data', 'ReportFilterController@getFilterData')->name('getReportFilterData');
            Route::post('report/get-filters', 'ReportFilterController@index')->name('getReportFilters');
            Route::post('report/delete-saved-filter', 'ReportFilterController@destroy')->name('deleteReportFilter');

            Route::get('report/employee-leave-report', 'Reports\EmployeeLeaveController@userLeaveReport')->name('userLeaveReport');
            Route::get('report/get-user-leave-report', 'Reports\EmployeeLeaveController@getUserLeaveReport')->name('getUserLeaveReport');
        });

        Route::group(['middleware' => ['permission:view-project-cost']], function () {
            Route::post('projects/get-project-cost-details-with-filter', 'Projects\ActiveController@getProjectCostDetailsWithFilter');
            Route::get('report/project-cost', 'Reports\Financial\ProjectCostController@projectCost')->name('ProjectCost');
            Route::get('report/get-project-cost-details', 'Reports\Financial\ProjectCostController@getProjectCostDetails');
            Route::get('report/project-costs-table', 'Reports\Financial\ProjectCostController@projectCostTable')->name('ProjectCostTable');
        });

        Route::group(['middleware' => ['permission:manage-salary-hike']], function () {
            Route::resource('salary-hike', 'Payroll\SalaryHikeController');
            Route::any('salary-hike-search', 'Payroll\SalaryHikeController@searchSalaryHike')->name('salary-hike-search');
            Route::get('salary-hike/employee/{id}', 'Payroll\SalaryHikeController@employeeHikeHistory')->name('employeeHikeHistory');
        });

        Route::group(['middleware' => ['permission:manage-leave']], function () {
            Route::get('pending-leave-applications', 'Leaves\PendingApplicationsController@adminIndex')->name('adminIndex');
            Route::get('previous-leave-applications', 'Leaves\ApprovedApplicationsController@previousApplications')->name('previousApplications');
            Route::post('list-previous-applications', 'Leaves\ApprovedApplicationsController@listPreviousApplications')->name('listPreviousApplications');
            Route::post('accept-leave-admin', 'Leaves\PendingApplicationsController@acceptLeave')->name('acceptLeave');
            Route::post('reject-leave-admin', 'Leaves\PendingApplicationsController@rejectLeave')->name('rejectLeave');
            Route::post('view-remainig-leaves', 'Leaves\PendingApplicationsController@getRemainigLeaves')->name('getRemainigLeaves');
            Route::post('get-all-leave-applications', 'Leaves\Apply\ApplyLeaveController@getLeaveApplications')->name('getLeaveApplications');
            Route::post('get-all-pending-leave-applications', 'Leaves\PendingApplicationsController@getPendingApplications')->name('getPendingApplications');
            Route::post('mark-as-lop', 'Leaves\Apply\ApplyLeaveController@markAsLop')->name('markAsLop');
            Route::get('assign-leave', 'Leaves\AssignLeaveController@assignLeave')->name('assignLeave');
            Route::post('get-user-leave-applications', 'Leaves\AssignLeaveController@getUserLeaveApplications')->name('getUserLeaveApplications');

            //Compensatory applications
            Route::get('compensatory-applications', 'Leaves\CompensatoryApplicationsController@compensatoryApplications')->name('compensatoryApplications');
            Route::post('accept-application-admin', 'Leaves\CompensatoryApplicationsController@acceptApplication')->name('acceptApplication');
            Route::post('reject-application-admin', 'Leaves\CompensatoryApplicationsController@rejectApplication')->name('rejectApplication');
            Route::POST('application-search', 'Leaves\CompensatoryApplicationsController@applicationSearch')->name('applicationSearch');
        });

        Route::group(['middleware' => ['permission:manage-timesheets']], function () {
            Route::get('/weekly-report/users/{id?}', 'UserTimesheetController@viewSheetUser')->name('viewSheetUser');
            Route::post('admin-timesheet-search-user', 'UserTimesheetController@adminTimesheetSearchUser')->name('adminTimesheetSearchUser');
            Route::get('weekly-report/projects', 'UserTimesheetController@viewSheetProject')->name('viewSheetProject');
            Route::post('admin-timesheet-search-project', 'UserTimesheetController@adminTimesheetSearchProject')->name('adminTimesheetSearchProject');
            Route::get('weekly-report/clients', 'UserTimesheetController@viewSheetClient')->name('viewSheetClient');
            Route::post('admin-timesheet-search-client', 'UserTimesheetController@adminTimesheetSearchClient')->name('adminTimesheetSearchClient');
            Route::get('client-monthly', 'TimesheetController@clientMonthly')->name('clientMonthly');
            Route::get('user-monthly', 'TimesheetController@userMonthly')->name('userMonthly');
            Route::post('user-month-search', 'TimesheetController@userMonthSearch')->name('userMonthSearch');
            Route::get('create-user-monthly', 'TimesheetController@createUserMonthly')->name('createUserMonthly');
            Route::get('daterange-reports/users', 'TimesheetController@userDaterange')->name('userDaterange');
            Route::post('user-daterange-search', 'TimesheetController@userDaterangeSearch')->name('userDaterangeSearch');
            Route::get('daterange-reports/projects', 'TimesheetController@projectDaterange')->name('projectDaterange');
            Route::post('project-daterange-search', 'TimesheetController@projectDaterangeSearch')->name('projectDaterangeSearch');
            Route::get('daterange-reports/clients', 'TimesheetController@clientDaterange')->name('clientDaterange');
            Route::post('client-daterange-search', 'TimesheetController@clientDaterangeSearch')->name('clientDaterangeSearch');

            Route::get('get-timesheets', 'NewTimesheetController@index')->name('newTimeSheet');
            Route::post('export-timesheet', 'NewTimesheetController@exportTimesheet')->name('newTimeSheetExport');
            Route::post('project-new-daterange-search', 'NewTimesheetController@projectDaterangeSearch');
        });

        Route::group(['middleware' => ['permission:apply-leave']], function () {
            Route::resource('apply-leave', 'Leaves\Apply\ApplyLeaveController');
            Route::post('get-leave-applications', 'Leaves\Apply\ApplyLeaveController@getLeaveApplications')->name('getMyLeaveApplications');
            Route::get('/cancel-leave/{id}', 'Leaves\Apply\ApplyLeaveController@cancelLeave')->name('cancelLeave');

            //Compensatory
            Route::resource('compensations', 'Leaves\Apply\ApplyCompensatoryOffController');
            Route::post('user-search', 'Leaves\Apply\ApplyCompensatoryOffController@userSearch')->name('userSearch');
        });

        Route::group(['middleware' => ['permission:manage-issue-records']], function () {
            Route::get('/issue-records', 'Tools\IssueRecordController@index')->name('issue-records.index');
            Route::post('/issue-records', 'Tools\IssueRecordController@store')->name('issue-records.store');
            Route::post('/issue-categories', 'Tools\IssueRecordController@storeCategory')->name('issue-records.storeCategory');
            Route::post('issue-record-search', 'Tools\IssueRecordController@issueRecordsSearch')->name('issueRecordsSearch');
            Route::get('/issue-records/{id}', 'Tools\IssueRecordController@show')->name('issue-records.show');
            Route::get('/issue-records/{id}/edit', 'Tools\IssueRecordController@edit')->name('issue-records.edit');
            Route::patch('/issue-records/{id}', 'Tools\IssueRecordController@update')->name('issue-records.update');
            Route::delete('/issue-records/{id}', 'Tools\IssueRecordController@destroy')->name('issue-records.delete');
        });

        Route::group(['middleware' => ['permission:access-my-credentials']], function () {
            Route::resource('my-credentials', 'Tools\MyCredentialsController');
        });
        Route::group(['middleware' => ['permission:view-project-credentials|manage-project-credentials']], function () {
            Route::get('/share-credentials/{id}', 'NewCredentialsController@shareCredentials');
            Route::post('/save-share-credentials', 'NewCredentialsController@mailshareCredentials')->name('shareCredentials');
            Route::get('/accept-credential/{credentialId}/{id}', 'NewCredentialsController@saveshareCredentials');
            Route::get('/user-credentials/{id}', 'NewCredentialsController@showUserCredential');
            Route::resource('credentials', 'NewCredentialsController');
        });

        Route::post('update-task-detail', 'TaskController@updateDetails')->name('updateDetails');
        Route::post('task-reject', 'TaskRejectionController@rejectionUpdate')->name('rejectionUpdate');
        Route::post('task-reject-qa', 'TaskRejectionController@rejectionQaUpdate')->name('rejectionQaUpdate');
        Route::delete('task-reject-delete/{id}', 'TaskRejectionController@deleteRejection')->name('deleteTaskRejection');
        Route::get('get-list', 'Projects\ActiveController@getList')->name('getList');
        Route::post('get-single-user', 'Users\UserController@getSingleUserAjax');
        Route::post('get-users-grid', 'Users\UserController@getUsersGridAjax');
        Route::post('get-client-grid', 'Users\ClientController@getClientsGrid')->name('getClientGrid');
        Route::post('get-newsletter-grid', 'NewsletterController@getNewslettersGrid');
        Route::post('get-single-client', 'Users\ClientController@getSingleClientAjax');
        Route::get('/', 'HomeController@index')->name('home');
        Route::post('/search', 'HomeController@search')->name('global-search');
        Route::post('send-timer-notification', 'HomeController@sendAlert')->name('sendAlert');
        Route::get('/', 'HomeController@index')->name('dashboards');
        Route::get('profile', 'HomeController@showProfile')->name('showProfile');

        Route::group(['middleware' => ['permission:access-my-tasks']], function () {
            Route::get('/tasks/{id}', 'TaskController@show')->name('tasks.show');
            Route::resource('ongoing-tasks', 'EmployeeTaskController');
            Route::get('upcoming-tasks', 'EmployeeTaskController@showUpcomingTasks')->name('upcomingTasks');
            Route::get('completed-tasks', 'EmployeeTaskController@showCompletedTasks')->name('completedTasks');
            Route::post('ongoing-tasks-list', 'EmployeeTaskController@listOngoingTasks')->name('listOngoingTasks');
            Route::post('completed-tasks-list', 'EmployeeTaskController@listCompletedTasks')->name('listCompletedTasks');
            Route::post('upcoming-tasks-list', 'EmployeeTaskController@listUpcomingTasks')->name('listUpcomingTasks');
        });

        Route::group(['middleware' => ['permission:view-newsletters']], function () {
            Route::get('/newsletters', 'NewsletterController@index')->name('newsletters.index');
        });

        Route::group(['middleware' => ['permission:manage-newsletters']], function () {
            Route::post('/newsletters', 'NewsletterController@store')->name('newsletters.store');
            Route::get('/newsletters/{id}/edit', 'NewsletterController@edit')->name('newsletters.edit');
            Route::patch('/newsletters/{id}', 'NewsletterController@update')->name('newsletters.update');
            Route::delete('/newsletters/{id}', 'NewsletterController@destroy')->name('newsletters.delete');
        });

        Route::group(['middleware' => ['permission:manage-dsr']], function () {
            Route::get('daily-status-report', 'Reports\DailyStatusController@dailyStatusReport')->name('dailyStatusReport');
            Route::post('daily-status-report', 'Reports\DailyStatusController@dailyStatusReportSearch')->name('dailyStatusReportSearch');
        });

        Route::group(['middleware' => 'role:employee'], function () {
            Route::get('employee-dashboard', 'HomeController@showEmployeeDashboard')->name('employeeDashboard');
        });

        Route::group(['middleware' => 'permit.santa'], function () {
            Route::group(['middleware' => 'role:hr-manager|hr-associate'], function () {
                Route::get('santa-members', 'SantaMemberController@index')->name('santa-members.index');
                Route::post('/santa-members', 'SantaMemberController@store')->name('santa-members.store');
                Route::get('/santa-members/{id}/edit', 'SantaMemberController@edit')->name('santa-members.edit');
                Route::get('/santa-members/{id}/edit', 'SantaMemberController@edit')->name('santa-members.edit');
                Route::patch('/santa-members/{id}', 'SantaMemberController@update')->name('santa-members.update');
                Route::delete('/santa-members/{id}', 'SantaMemberController@destroy')->name('santa-members.delete');
            });
            Route::group(['middleware' => 'role:hr-manager'], function () {
                Route::get('/santa-members/set-santa', 'SantaMemberController@setSanta')->name('santa-members.setSanta');
                Route::get('/santa-members/view-santa', 'SantaMemberController@viewSanta')->name('santa-members.viewSanta');
                Route::get('/santa-members/reset-santa', 'SantaMemberController@resetSanta')->name('santa-members.resetSanta');
            });

            Route::get('find-my-santa', 'SantaMemberController@findSanta')->name('find-my-santa');
            Route::get('confirm-santa', 'SantaMemberController@confirmSanta')->name('confirm-santa');
            Route::post('send-santa-message', 'SantaMemberController@sendMessage')->name('send-santa-message');
            Route::post('send-santa-wish', 'SantaMemberController@sendWish')->name('send-santa-wish');
        });

        Route::group(['middleware' => ['permission:manage-daily-checklists']], function () {
            Route::resource('checklists', 'Checklists\ManageChecklistsController');
            Route::get('use-checklists', 'Checklists\ChecklistController@useChecklists')->name('useChecklists');
            Route::post('update-user-checklist', 'Checklists\ChecklistController@updateUserChecklist')->name('updateUserChecklist');
            Route::get('checklist-report', 'Checklists\ReportsController@checklistReport')->name('checklistReport');
            Route::post('search-checklist-report', 'Checklists\ReportsController@searchChecklistReport')->name('searchChecklistReport');
            Route::post('save-checklist', 'Checklists\ChecklistController@saveChecklist')->name('saveChecklist');
            Route::post('share-checklist', 'Checklists\ManageChecklistsController@shareChecklist')->name('shareChecklist');
            Route::get('employee-checklist', 'Checklists\ManageChecklistsController@employeeChecklist')->name('employeeChecklist');
            Route::post('search-checklist', 'Checklists\ManageChecklistsController@searchChecklist')->name('searchChecklist');
        });

        Route::group(['middleware' => ['permission:manage-recruitments']], function () {
            Route::resource('recruitments', 'Miscellaneous\RecruitmentController');
            Route::get('recruitment-schedules', 'Miscellaneous\RecruitmentSchedulesController@listSchedules')->name('listSchedules');
            Route::any('search-candidate', 'Miscellaneous\RecruitmentController@searchCandidate')->name('searchCandidate');
            Route::post('search-schedule', 'Miscellaneous\RecruitmentSchedulesController@searchSchedule')->name('searchSchedule');
            Route::post('update-schedule', 'Miscellaneous\RecruitmentController@updateSchedule')->name('updateSchedule');
            Route::post('get-schedule', 'Miscellaneous\RecruitmentController@getSchedule')->name('getSchedule');
        });

        Route::resource('qa-feedback', 'TaskRejectionController');
        Route::post('user-feedback-search', 'TaskRejectionController@userFeedbackSearch')->name('userFeedbackSearch');
        Route::group(['middleware' => ['permission:view-qa-feedback']], function () {
            Route::resource('qa-feedback', 'TaskRejectionController');
            Route::post('user-feedback-search', 'TaskRejectionController@userFeedbackSearch')->name('userFeedbackSearch');
        });
        Route::post('content-image-upload', ['as' => 'content.image-upload', 'uses' => 'EditorFileUploadController@fileUpload']);
        Route::resource('work-notes', 'Tools\WorkNotesController');
        Route::get('get-typhead-data-project', 'Projects\ActiveController@getTypheadDataProject')->name('getTypheadDataProject');
        Route::post('load-project-status', 'Projects\ActiveController@loadProjectStatus')->name('loadProjectStatus');
        Route::post('get-tasks-project-user', 'TaskController@getTasksProjectUser')->name('getTasksProjectUser');
        Route::get('get-typhead-data-user', 'Users\UserController@getTypheadDataUser')->name('getTypheadDataUser');
        Route::get('get-create-form', 'Users\UserController@getCreateUserData')->name('getCreateUserData');
        Route::get('get-typhead-data-client', 'Users\ClientController@getTypheadDataClient')->name('getTypheadDataClient');
        Route::get('/view-details/{id}', 'TaskController@show')->name('viewDetails');
        Route::post('/check-subtasks', 'TaskController@checkSubTasks')->name('checkSubTasks');
        Route::post('/change-status', 'TaskController@changeStatus')->name('changeStatus');
        Route::post('change-status-finish', 'TaskController@changeStatusFinish')->name('changeStatusFinish');
        Route::post('accept-completion', 'TaskController@acceptCompletion')->name('acceptCompletion');
        Route::post('reject-completion', 'TaskController@rejectCompletion')->name('rejectCompletion');
        Route::post('get-user-session', 'TaskController@getUserSession')->name('getUserSession');
        Route::post('export-session', 'TaskController@exportSession')->name('sessionExport');
        Route::get('/taskDetails/{id}', 'EmployeeTaskController@show')->name('taskDetails');
        Route::post('get-tasks-session', 'EmployeeTaskController@getTaskSession')->name('getTaskSession');
        Route::view('/settings', 'settings.index')->name('settings');
        Route::resource('task-session', 'TaskSessionController');
        Route::post('/add-task-session', 'TaskSessionController@addTaskSession')->name('addTaskSession');
        Route::post('stop-session', 'TaskSessionController@stopSession')->name('stopSession');
        Route::post('pause-session', 'TaskSessionController@pauseSession')->name('pauseSession');
        Route::get('check-session', 'TaskSessionController@checkSession')->name('checkSession');
        Route::post('check-existing-session', 'TaskSessionController@checkExistingSession')->name('checkExistingSession');
        Route::post('check-if-session-is-stopped', 'TaskSessionController@checkIfSessionIsStopped')->name('checkIfSessionIsStopped');
        Route::post('update-task-checklist', 'TaskController@updateChecklist')->name('updateChecklist');
        Route::get('user-wish-notified', 'Users\UserController@wishNotified')->name('wishNotified');
        Route::get('eod-report-notified', 'Users\UserController@eodReportNotified')->name('eodReportNotified');
        Route::get('/tasks/view-comments/{id}', 'TaskController@viewComments')->name('tasks.viewComments');
        Route::post('get-task-comments', 'TaskController@viewCommentsTask')->name('viewCommentsTasks');
        Route::post('check-whether-exceeds-time', 'TaskController@checkWhetherExceedsTime')->name('CheckWhetherExceedsTime');
        Route::post('add-time-exceed-reason', 'TaskController@addTimeExceedReason')->name('addTimeExceedReason');
        Route::post('check-whether-exceeds-time-with-reason', 'TaskController@checkWhetherExceedsTimeWithReason')->name('CheckWhetherExceedsTimeWithReason');
        Route::post('check-exceed-time', 'TaskController@checkExceedTime')->name('checkExceedTime');
        Route::get('get-autocomplete-data-task', 'TaskController@getAutocompleteDataTask')->name('getAutocompleteDataTask');
        Route::get('get-autocomplete-data-project', 'TaskController@getAutocompleteDataProject')->name('getAutocompleteDataProject');
        Route::post('save-daily-status-report', 'Reports\DailyStatusController@saveDailyStatusReport')->name('dailyStatusReport.store');
        Route::post('update-order', 'TaskController@updateOrder')->name('updateOrder');
        Route::get('/search-board/{id}', 'TaskController@searchAgile')->name('searchAgile');
        Route::get('/agile-board/{id}', 'TaskController@viewAgile')->name('viewAgile');
        Route::post('upload-task-files', 'TaskController@uploadTaskFiles')->name('uploadTaskFiles');
        Route::post('get-documents', 'TaskController@getDocuments')->name('getDocuments');
        Route::post('add-branch', 'BranchController@store')->name('addBranch');
        Route::post('delete-branch', 'BranchController@destroy')->name('deleteBranch');

        Route::group(['middleware' => ['permission:manage-guidelines']], function () {
            Route::resource('guidelines', 'Tools\GuidelineController');
            Route::post('load-guideline', 'Tools\GuidelineController@loadGuideline')->name('loadGuideline');
            Route::get('get-typhead-categories', 'Tools\GuidelineController@getList')->name('getGuidelineList');
            Route::post('get-category-list', 'Tools\GuidelineController@getCategoryList')->name('getCategoryList');
            Route::post('add-tag', 'Tools\GuidelineController@addTag')->name('addTag');
        });
        Route::post('clear-chart-cache', 'Dashboard\EmployeeStatusController@clearChartCache')->name('clearChartCache');
        Route::get('check-task-session', 'TaskSessionController@checkTaskSession')->name('checkTaskSession');

        Route::group(['middleware' => ['permission:manage-payroll']], function () {
            Route::resource('salary-component', 'Payroll\SalaryComponentController');
            Route::resource('payroll', 'Payroll\ManagePayrollController');
            Route::patch('payroll/{id}/update', 'Payroll\ManagePayrollController@update')->name('payrollUpdate');
            Route::post('payroll-export', 'Payroll\ManagePayrollController@export')->name('payroll.export');
            Route::get('payrolls', 'Payroll\ManagePayrollController@index')->name('payroll-filter.index');
            Route::get('payrolls/{year}', 'Payroll\ManagePayrollController@filter')->name('payroll.filter');
            Route::get('payroll-user', 'Payroll\ManagePayrollController@indexEmployee')->name('payrolluserIndex');
            Route::get('payroll-user/{id}', 'Payroll\ManagePayrollController@showEmployee')->name('payrollUserIndex');
            Route::get('payroll-user/{id}/edit', 'Payroll\ManagePayrollController@editEmployee')->name('payroll-user.edit');
            Route::patch('payroll-user/{id}', 'Payroll\ManagePayrollController@updateEmployee')->name('payroll-user.update');
            Route::get('payroll-user/{id}/{monthYear}', 'Payroll\ManagePayrollController@showEmployee')->name('payroll-user.view');
            Route::get('payroll-user/{id}/{monthYear}/export', 'Payroll\ManagePayrollController@exportEmployee')->name('payroll-user.export');
        });

        Route::group(['middleware' => ['permission:view-payslip']], function () {
            Route::resource('payslip', 'EmployeePayslipController')->except(['edit', 'update', 'destroy']);
            Route::get('payslip-export/{monthYear}', 'EmployeePayslipController@export')->name('payslip.export');
        });

        Route::group(['middleware' => ['permission:access-email-signature']], function () {
            Route::get('/email-signature', 'Tools\EmailSignatureController@index')->name('emailSignature');
        });

        // Route::group(['middleware' => ['permission:manage-assets']], function () {
        Route::resource('assets', 'ItAssets\AssetsController');
        Route::any('asset-search', 'ItAssets\AssetsController@searchAsset')->name('assets.asset-search');
        Route::post('asset-assign', 'ItAssets\AssetsController@assignAsset')->name('assets.assign');
        Route::get('ticket-raised-assets', 'ItAssets\TicketsController@ticketRaisedAssetList')->name('assets.ticketRaisedAssetList');
        Route::get('ticket-status-edit/{id}', 'ItAssets\TicketsController@ticketStatusEdit')->name('assets.ticket-status-edit');
        Route::post('ticket-status-update', 'ItAssets\TicketsController@ticketStatusUpdate')->name('assets.ticket-status-update');
        Route::any('ticket-raised-asset-search', 'ItAssets\TicketsController@searchTicketAsset')->name('assets.ticket-raised-asset-search');
        Route::get('asset-type-attributes', 'ItAssets\TypesController@getAssetTypeAttributes')->name('asset-types.getAssetTypeAttributes');
        Route::resource('asset-types', 'ItAssets\TypesController');
        Route::resource('asset-vendors', 'ItAssets\VendorsController');
        Route::resource('attributes', 'ItAssets\AttributesController');
        Route::delete('/delete-attribute-value/{attributeValue}', 'ItAssets\AttributesController@deleteAttributeValue')->name('delete-attribute-value');
        Route::delete('/assets/delete-document/{id}', 'ItAssets\AssetsController@deleteDoc')->name('assets.deleteDoc');
        Route::resource('ticket-status', 'ItAssets\TicketStatusController');
        Route::post('upload-asset-files', 'ItAssets\AssetsController@uploadAssetFiles')->name('uploadAssetFiles');
        Route::post('export-excel-assets', 'ItAssets\AssetsController@exportExcelAssets')->name('exportExcelAssets');
        // });

        // Route::group(['middleware' => ['permission:view-assets']], function () {
        Route::get('employee-asset-list', 'ItAssets\MyAssetsController@employeeAssetList')->name('assets.employeeAssetList');
        Route::post('return-asset/{id}', 'ItAssets\MyAssetsController@returnAsset')->name('assets.returnAsset');
        Route::post('ticket-raise-asset', 'ItAssets\MyAssetsController@ticketRaiseAsset')->name('assets.ticketRaiseAsset');
        Route::get('employee-ticket-raised-assets', 'ItAssets\MyTicketsController@employeeTicketRaisedAssetList')->name('assets.employeeTicketRaisedAssetList');
        Route::get('ticket-issue-update/{id}', 'ItAssets\TicketsController@ticketIssueUpdate')->name('assets.ticketIssueUpdate');
        Route::post('issue-update', 'ItAssets\TicketsController@issueUpdate')->name('assets.issueUpdate');
        Route::any('employee-ticket-raised-asset-search', 'ItAssets\MyTicketsController@searchEmployeeTicketAsset')->name('assets.employee-ticket-raised-asset-search');
        Route::post('get-asset-documents', 'ItAssets\AssetsController@getDocuments')->name('getAssetDocuments');

        // });
    });
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware(['guest'])->group(function () {
    //Route::get('/forgot-password', 'Auth\ForgotPasswordController')->name('password.request');
    Route::post('/forgot-password', 'Auth\ForgotPasswordController@handle')->name('password.email');
    Route::get('/reset-password/{token}', 'Auth\ResetPasswordController@reset')->name('password.reset');
    Route::post('/reset-password', 'Auth\ResetPasswordController@handle')->name('password.update');
});

Route::post('/save-subscription/{id}', 'HomeController@saveSubscription');

//TODO::Remove this and InteriaTest file
Route::get('/inertia-test', function () {
    return Inertia::render('InertiaTest', ['version' => '1.1']);
})->name('subscription');
