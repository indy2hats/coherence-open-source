const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// Common style
mix.styles([
    'public/css/bootstrap.min.css',
	'public/font-awesome/css/font-awesome.css',
	'public/css/animate.css',
	'public/css/style.css',
	'public/css/plugins/dataTables/datatables.min.css',
	'public/css/plugins/toastr/toastr.min.css',
	'public/css/my-style.css',
], 'public/css/all.css');

// Common JS
mix.scripts([
    'public/js/jquery-3.1.1.min.js',
	'public/js/plugins/jquery-ui/jquery-ui.min.js',
	'public/js/bootstrap.min.js',
	'public/js/plugins/metisMenu/jquery.metisMenu.js',
	'public/js/plugins/slimscroll/jquery.slimscroll.min.js',
	'public/js/jquery.cookie.js',
	'public/js/plugins/toastr/toastr.min.js',
	'public/js/plugins/dataTables/datatables.min.js',
	'public/js/plugins/summernote/summernote.min.js',
	'public/js/plugins/chosen/chosen.jquery.js',
	'public/js/inspinia.js',
	'public/js/plugins/pace/pace.min.js',
	'public/js/moment.min.js',
	'public/js/plugins/toastr/toastr.min.js',
	'public/js/plugins/firework/jquery.fireworks.js',
	'public/js/resources/partials/scripts-include.js',
], 'public/js/all.js');


// checklists/report
mix.scripts([
    'public/js/resources/alerts/script.js',
], 'public/js/resources/alerts/script-min.js');

// checklists/checklist
mix.scripts([
    'public/js/resources/checklists/checklist/script.js',
], 'public/js/resources/checklists/checklist/script-min.js');

// checklists/report
mix.scripts([
    'public/js/resources/checklists/report/script.js',
], 'public/js/resources/checklists/report/script-min.js');

// checklists/manage-checklist
mix.scripts([
    'public/js/resources/checklists/manage-checklist/script.js',
], 'public/js/resources/checklists/manage-checklist/script-min.js');

// client-sheet
mix.scripts([
    'public/js/resources/client-sheet/script.js',
    'public/js/resources/tasks/comments-script.js',
], 'public/js/resources/client-sheet/script-min.js');

// clients
mix.scripts([
    'public/js/resources/clients/script.js',
    'public/js/plugins/typehead/bootstrap3-typeahead.min.js',
], 'public/js/resources/clients/script-min.js');

// credentials
mix.scripts([
    'public/js/resources/credentials/script.js',
], 'public/js/resources/credentials/script-min.js');


// dashboard
mix.scripts([
    'js/plugins/sparkline/jquery.sparkline.min.js',
    'public/js/resources/dashboard/script.js',
], 'public/js/resources/dashboard/script-min.js');

// dashboard-employee
mix.scripts([
    'public/js/resources/dashboard/timer-script.js',
    'public/js/resources/dashboard/employee-dashboard.js',
], 'public/js/resources/dashboard/employee-script-min.js');

// dashboard-client
mix.scripts([
	'public/js/resources/dashboard/timer-script.js',
    'public/js/resources/dashboard/clientdashboard-script.js',
], 'public/js/resources/dashboard/client-script-min.js');

// easy-access
mix.scripts([
    'public/js/resources/easy-access/script.js',
], 'public/js/resources/easy-access/script-min.js');

// employeetask- merging 3 scripts of upcomming, ongoing and completed
mix.scripts([
    'public/js/resources/employeetasks/dataTable-script.js',
], 'public/js/resources/employeetasks/dataTable-script-min.js');

// employeetask- details
mix.scripts([
    'public/js/resources/employeetasks/script.js',
], 'public/js/resources/employeetasks/script-min.js');

// general/overheads
mix.scripts([
    'public/js/resources/general/overheads/script.js',
], 'public/js/resources/general/overheads/script-min.js');

// issue-records
mix.scripts([
    'public/js/resources/issue-records/script.js',
], 'public/js/resources/issue-records/script-min.js');

// leave
mix.scripts([
    'public/js/resources/leave/script.js',
], 'public/js/resources/leave/script-min.js');

// leave/adminleave
mix.scripts([
    'public/js/resources/leave/adminleave/script.js',
], 'public/js/resources/leave/adminleave/script-min.js');

// leave/assignLeave
mix.scripts([
    'public/js/resources/leave/assignLeave/script.js',
], 'public/js/resources/leave/assignLeave/script-min.js');

// newsletters
mix.scripts([
    'public/js/resources/newsletters/script.js',
], 'public/js/resources/newsletters/script-min.js');

// profile
mix.scripts([
    'public/js/resources/profile/script.js',
], 'public/js/resources/profile/script-min.js');

// projects
mix.scripts([
    'public/js/resources/projects/script.js',
], 'public/js/resources/projects/script-min.js');

// projects/project_files
mix.scripts([
    'public/js/resources/projects/project_files/script.js',
], 'public/js/resources/projects/project_files/script-min.js');

// projects/project_credentials
mix.scripts([
    'public/js/resources/projects/project_credentials/script.js',
], 'public/js/resources/projects/project_credentials/script-min.js');

// projects-details
mix.scripts([
    'public/js/resources/projects/view-script.js',
    'public/js/resources/tasks/tag-script.js',
], 'public/js/resources/projects/view-script-min.js');

// qa-feedback
mix.scripts([
    'public/js/resources/qa-feedback/script.js',
], 'public/js/resources/qa-feedback/script-min.js');

// reports/clients
mix.scripts([
    'public/js/resources/reports/clients/script.js',
], 'public/js/resources/reports/clients/script-min.js');

// reports/overduetasks
mix.scripts([
    'public/js/resources/reports/overduetasks/script.js',
], 'public/js/resources/reports/overduetasks/script-min.js');

// reports/performance
mix.scripts([
    'public/js/resources/reports/performance/script.js',
], 'public/js/resources/reports/performance/script-min.js');

// reports/projects
mix.scripts([
    'public/js/resources/reports/projects/script.js',
], 'public/js/resources/reports/projects/script-min.js');

// reports/status_report
mix.scripts([
    'public/js/resources/reports/status_report/script.js',
], 'public/js/resources/reports/status_report/script-min.js');

// reports/users
mix.scripts([
    'public/js/resources/reports/users/script.js',
], 'public/js/resources/reports/users/script-min.js');

// settings/access-levels
mix.scripts([
    'public/js/resources/settings/access-levels/script.js',
], 'public/js/resources/settings/access-levels/script-min.js');

// settings/manage-overhead
mix.scripts([
    'public/js/resources/settings/manage-overhead/script.js',
], 'public/js/resources/settings/manage-overhead/script-min.js');

// settings/manageholiday
mix.scripts([
    'public/js/resources/settings/manageholiday/script.js',
], 'public/js/resources/settings/manageholiday/script-min.js');

// settings/session-types
mix.scripts([
    'public/js/resources/settings/session-types/script.js',
], 'public/js/resources/settings/session-types/script-min.js');

// settings/status-types
mix.scripts([
    'public/js/resources/settings/status-types/script.js',
], 'public/js/resources/settings/status-types/script-min.js');

// task-bounds
mix.scripts([
    'public/js/resources/task-bounds/script.js',
], 'public/js/resources/task-bounds/script-min.js');

// work-notes
mix.scripts([
    'public/js/resources/work-notes/script.js',
], 'public/js/resources/work-notes/script-min.js');

// users
mix.scripts([
    'public/js/resources/users/script.js',
], 'public/js/resources/users/script-min.js');

// timesheets/client-daterange
mix.scripts([
    'public/js/resources/timesheets/client-daterange/script.js',
], 'public/js/resources/timesheets/client-daterange/script-min.js');

// timesheets/client-monthly
mix.scripts([
    'public/js/resources/timesheets/client-monthly/script.js',
], 'public/js/resources/timesheets/client-monthly/script-min.js');

// timesheets/clientsheet
mix.scripts([
    'public/js/resources/timesheets/clientsheet/script.js',
], 'public/js/resources/timesheets/clientsheet/script-min.js');

// timesheets/project-daterange
mix.scripts([
    'public/js/resources/timesheets/project-daterange/script.js',
], 'public/js/resources/timesheets/project-daterange/script-min.js');

// timesheets/projectsheet
mix.scripts([
    'public/js/resources/timesheets/projectsheet/script.js',
], 'public/js/resources/timesheets/projectsheet/script-min.js');

// timesheets/user-daterange
mix.scripts([
    'public/js/resources/timesheets/user-daterange/script.js',
], 'public/js/resources/timesheets/user-daterange/script-min.js');

// timesheets/user-monthly
mix.scripts([
    'public/js/resources/timesheets/user-monthly/script.js',
], 'public/js/resources/timesheets/user-monthly/script-min.js');

// timesheets/usersheet
mix.scripts([
    'public/js/resources/timesheets/usersheet/script.js',
], 'public/js/resources/timesheets/usersheet/script-min.js');

// timesheets/userstatus
mix.scripts([
    'public/js/resources/timesheets/userstatus/script.js',
], 'public/js/resources/timesheets/userstatus/script-min.js');

// tasks
mix.scripts([
    'public/js/resources/tasks/script.js',
    'public/js/resources/tasks/tag-script.js'
], 'public/js/resources/tasks/script-min.js');

// tasks-view
mix.scripts([
    'public/js/resources/tasks/view-script.js',
    'public/js/resources/tasks/tag-script.js',
    'public/js/resources/tasks/timer-script.js',
    'public/js/resources/tasks/comments-script.js',
    'public/js/resources/tasks/sub-task/script.js',
], 'public/js/resources/tasks/view-script-min.js');

// guidelines
mix.scripts([
    'public/js/resources/guidelines/script.js',
], 'public/js/resources/guidelines/script-min.js');

// guidelines-show
mix.scripts([
    'public/js/resources/guidelines/script.js',
], 'public/js/resources/guidelines/script-min.js');

// base-currency
mix.scripts([
    'public/js/resources/settings/base-currency/script.js',
], 'public/js/resources/settings/base-currency/script-min.js');

// compensatory-admin
mix.scripts([
    'public/js/resources/compensatory/admin/script.js',
], 'public/js/resources/compensatory/admin/script-min.js');

// compensatory
mix.scripts([
    'public/js/resources/compensatory/script.js',
], 'public/js/resources/compensatory/script-min.js');

// recruitments
mix.scripts([
    'public/js/resources/recruitments/script.js',
], 'public/js/resources/recruitments/script-min.js');

