<!-- --------------------side nav -start- --------------------------------->
<nav class="navbar-default navbar-static-side" role="navigation">
    <a class="navbar-minimalize minimalize-styl-2 btn  toggle-nav-user" href="#">
        <i class="fa fa-angle-left"></i>
    </a>
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="profile-element">
                    <span class="avatar">
                        <img id="header_pic" alt="image" class="img-circle" src="@if(Auth::user()->image_path){{ asset('storage/'.Auth::user()->image_path) }}@else{{ asset('img/user.jpg') }}@endif" style="width: 50px" />
                    </span>
                    <span class="user-basic">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block"> <strong class="font-bold">{{ Session::get('name') }}</strong>
                            </span>
                            <span class="small text-muted text-xs block desc">{{ Session::get('desg') }} </span>
                        </a>
                    </span>
                </div>
                <!-- mobile view -->
                <div class="logo-element">
                    <a href="{{ route('showProfile') }}">
                        <img id="header_pic" alt="image" class="img-circle" src="@if(Auth::user()->image_path){{ asset('storage/'.Auth::user()->image_path) }}@else{{ asset('img/user.jpg') }}@endif" />
                    </a>
                </div>
            </li>
            @can('view-dashboard')
            <li class="{{(request()->is('dashboard')) ? 'active' : ''}}">
                <a href="/dashboard" data-toggle="tooltip" data-placement="right" title="Dashboard"><i class="ri-dashboard-line"></i><span class="nav-label">Dashboard</span> </span></a>
            </li>
            @endcan
            @can('view-projects')
            <li class="{{ (request()->is('projects/*') || request()->is('projects') || request()->is('archived-projects') || request()->is('archived-project-search')) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Projects"><i class="ri-folder-3-line"></i><span class="nav-label">Projects</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{(request()->is('projects')) ? 'active' : ''}}">
                        <a href="{{route('projects.index')}}" data-toggle="tooltip" data-placement="right" title="Active Projects">
                            Active Projects
                        </a>
                    </li>
                    <li class="{{(request()->is('archived-project-search')||request()->is('archived-projects')) ? 'active' : ''}}">
                        <a href="{{route('archivedProjects')}}" data-toggle="tooltip" data-placement="right" title="Archived Tasks">
                           Archived Projects
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @unlessrole('client')
            @canany(['view-tasks','manage-tasks','access-my-tasks'])
            <li class="{{ request()->is('tasks') || (request()->is('ongoing-tasks')||request()->is('upcoming-tasks')||request()->is('completed-tasks')||request()->is('archived-tasks')||request()->is('archived-task-search')||request()->is('tasks/*')) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="My Task"><i class="ri-file-2-line"></i><span class="nav-label">Tasks</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @can('view-tasks')
                    <li class="{{(request()->is('tasks')) ? 'active' : ''}}">
                        <a href="{{route('tasks.index')}}" data-toggle="tooltip" data-placement="right" title="Active Tasks">
                            Active Tasks
                        </a>
                    </li>
                    @endcan
                    <li class="{{(request()->is('ongoing-tasks')) ? 'active' : ''}}"><a href="{{ route('ongoing-tasks.index') }}" data-toggle="tooltip" data-placement="right" title="Ongoing Tasks">My Ongoing Tasks</a></li>
                    <li class="{{(request()->is('upcoming-tasks')) ? 'active' : ''}}"><a href="{{ route('upcomingTasks') }}" data-toggle="tooltip" data-placement="right" title="Upcoming Tasks">My Upcoming Tasks</a></li>
                    <li class="{{(request()->is('completed-tasks')) ? 'active' : ''}}"><a href="{{ route('completedTasks') }}" data-toggle="tooltip" data-placement="right" title="Completed Tasks">My Completed Tasks</a></li>
                    @can('manage-tasks')
                    <li class="{{(request()->is('archived-task-search')||request()->is('archived-tasks')) ? 'active' : ''}}">
                        <a href="{{route('archivedTasks')}}" data-toggle="tooltip" data-placement="right" title="Archived Tasks">
                            Archived Tasks
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @else
            @canany(['view-tasks','manage-tasks'])
            <li class="{{ request()->is('tasks') ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="My Task"><i class="ri-file-2-line"></i> <span class="nav-label">Tasks</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @can('view-tasks')
                    <li class="{{(request()->is('tasks')) ? 'active' : ''}}">
                        <a href="{{route('tasks.index')}}" data-toggle="tooltip" data-placement="right" title="Active Tasks">
                            Active Tasks
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @endunlessrole

            @can('manage-daily-checklists')
            <li class="{{ request()->is('use-checklists') || (request()->is('checklists')||request()->is('checklist-report')) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Checklists"><i class="ri-list-check-3"></i> <span class="nav-label">Checklists</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{(request()->is('use-checklists')) ? 'active' : ''}}">
                        <a href="{{route('useChecklists')}}" data-toggle="tooltip" data-placement="right" title="Update Checklists">
                            Checklists
                        </a>
                    </li>
                    <li class="{{(request()->is('checklists')) ? 'active' : ''}}"><a href="{{ route('checklists.index') }}" data-toggle="tooltip" data-placement="right" title="Manage Checklists">Manage Checklists</a></li>
                    <li class="{{(request()->is('checklist-report')) ? 'active' : ''}}"><a href="{{route('checklistReport')}}" data-toggle="tooltip" data-placement="right" title="Daily checklist Reports">Reports</a></li>
                </ul>
            </li>
            @endcan

            @canany(['manage-timesheets','view-my-timesheet','view-my-team'])
            <li class="{{ request()->is('my-timesheet') || (request()->is('weekly-report/users') || request()->is('daterange-reports/users') || request()->is('weekly-report/projects') || request()->is('daterange-reports/projects')|| request()->is('weekly-report/clients') || request()->is('daterange-reports/clients')|| request()->is('get-timesheets')) || request()->is('my-team-timesheet') || request()->is('team-timesheet-search') ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Timesheet"><i class="ri-time-line"></i><span class="nav-label"> Timesheets</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @can('view-my-timesheet')
                    <li class="{{(request()->is('my-timesheet')) ? 'active' : ''}}">
                        <a href="{{ route('my-timesheet.index') }}" data-toggle="tooltip" data-placement="right" title="My Timesheet"> My Timesheet</a>
                    </li>
                    @endcan
                    @can('manage-team')
                    <li class="{{(request()->is('my-team-timesheet') || request()->is('team-timesheet-search')) ? 'active' : ''}}">
                        <a href="{{ route('my-team-timesheet.index') }}" data-toggle="tooltip" data-placement="right" title="My Team"> My Team</a>
                    </li>
                    @endcan
                    @can('manage-timesheets')
                    {{-- <li class="{{(request()->is('weekly-report/users') || request()->is('daterange-reports/users')) ? 'active' : ''}}"><a href=""> User</a>
                        <ul class="nav nav-third-level">
                            <li class="{{(request()->is('weekly-report/users')) ? 'active' : ''}}">
                                <a href="/weekly-report/users/">Week</a>
                            </li>
                            <li class="{{(request()->is('daterange-reports/users')) ? 'active' : ''}}">
                                <a href="{{ route('userDaterange') }}">Date Range</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{(request()->is('weekly-report/projects') || request()->is('daterange-reports/projects')) ? 'active' : ''}}"><a href=""> Project</a>
                        <ul class="nav nav-third-level">
                            <li class="{{(request()->is('weekly-report/projects')) ? 'active' : ''}}">
                                <a href="{{ route('viewSheetProject') }}">Week</a>
                            </li>
                            <li class="{{(request()->is('daterange-reports/projects')) ? 'active' : ''}}">
                                <a href="{{ route('projectDaterange') }}">Date Range</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{(request()->is('weekly-report/clients') || request()->is('daterange-reports/clients')) ? 'active' : ''}}"><a href=""> Client</a>
                        <ul class="nav nav-third-level">
                            <li class="{{(request()->is('weekly-report/clients')) ? 'active' : ''}}">
                                <a href="{{ route('viewSheetClient') }}">Week</a>
                            </li>
                            <li class="{{(request()->is('daterange-reports/clients')) ? 'active' : ''}}">
                                <a href="{{ route('clientDaterange') }}">Date Range</a>
                            </li>
                        </ul>
                    </li> --}}
                    <li class="{{(request()->is('get-timesheets')) ? 'active' : ''}}">
                        <a href="/get-timesheets" data-toggle="tooltip" data-placement="right" title="timesheets">Timesheets</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @unlessrole('client')
            <li class="{{ (request()->is('work-notes')) || (request()->is('issue-records*')) || request()->is('guidelines')|| (request()->is('credentials'))|| (request()->is('easy-access')) || (request()->is('qa-feedback')) || request()->is('my-credentials')|| request()->is('guidelines/*') ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Tools">
                <i class="ri-tools-line"></i><span class="nav-label">Tools</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{(request()->is('work-notes')) ? 'active' : ''}}">
                        <a href="{{route('work-notes.index')}}" data-toggle="tooltip" data-placement="right" title="Work Notes">
                            Work Notes
                        </a>
                    </li>
                    @can('manage-issue-records')
                    <li class="{{(request()->is('issue-records*')) ? 'active' : ''}}">
                        <a href="{{route('issue-records.index')}}" data-toggle="tooltip" data-placement="right" title="Issue Records">Issue Records
                        </a>
                    </li>
                    @endcan
                    @canany(['view-project-credentials','manage-project-credentials'])
                    <li class="{{(request()->is('credentials')) ? 'active' : ''}}">
                        <a href="{{route('credentials.index')}}" data-toggle="tooltip" data-placement="right" title="Project Credentials"> Project Credentials
                        </a>
                    </li>
                    @endcan
                    @can('access-my-credentials')
                    <li class="{{(request()->is('my-credentials')) ? 'active' : ''}}">
                        <a href="{{route('my-credentials.index')}}" data-toggle="tooltip" data-placement="right" title="My Credentials"> My Credentials
                        </a>
                    </li>
                    @endcan
                    @can('view-qa-feedback')
                    <li class="{{(request()->is('qa-feedback')) ? 'active' : ''}}">
                      <a href="{{route('qa-feedback.index')}}" data-toggle="tooltip" data-placement="right" title="QA Feedback"> QA Feedback
                       </a>
                    </li>
                    @endcan
                    <li class="{{(request()->is('easy-access')) ? 'active' : ''}}">
                        <a href="{{route('easyAccess')}}" data-toggle="tooltip" data-placement="right" title="Easy Access"> Easy Access
                        </a>
                    </li>
                    @can('access-email-signature')
                    <li class="{{(request()->is('email-signature')) ? 'active' : ''}}">
                        <a href="{{route('emailSignature')}}" data-toggle="tooltip" data-placement="right" title="Email Signature" target="_blank"> Email Signature
                        </a>
                    </li>
                    @endcan
                    @can('manage-guidelines')
                    <li class="{{(request()->is('guidelines'))|| request()->is('guidelines/*') ? 'active' : ''}}">
                        <a href="{{route('guidelines.index')}}" data-toggle="tooltip" data-placement="right" title="Guidelines"> Guidelines
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @else
            <li class="{{(request()->is('client-sheet')) ? 'active' : ''}}">
                <a href="{{ route('client-sheet') }}" data-toggle="tooltip" data-placement="right" title="Timesheet"><i class="ri-time-line"></i> <span class="nav-label">Timesheet</span></span></a>
            </li>
            @endunlessrole

            @can(['view-reports'])
            <li class="{{(request()->is('report/clients') || request()->is('report/projects') || request()->is('report/users') || request()->is('report/performance') || request()->is('task-bounce-list') || request()->is('task-bounce-report')|| request()->is('task-bounce-graph') || request()->is('report/user-accounts') || request()->is('daily-status-report')) || request()->is('report/employee-leave-report') || (request()->is('report/percentage-split-up') || request()->is('report/invoice-clearance-time') || request()->is('report/project-cost*') || request()->is('report/task-time-report') || request()->is('report/project-billability-report')) || (request()->is('report/project-billability-hours-graph'))  ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Report"><i class="fa fa-files-o" aria-hidden="true"></i> <span class="nav-label">Reports</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <!-- <li class="{{(request()->is('report/clients')) ? 'active' : ''}}"><a href="{{ route('clientReport') }}" data-toggle="tooltip" data-placement="right" title="Client Report">Clients</a></li>
                    <li class="{{(request()->is('report/projects')) ? 'active' : ''}}"><a href="{{ route('projectReport') }}" data-toggle="tooltip" data-placement="right" title="Project Report">Projects</a></li>
                    <li class="{{(request()->is('report/users')) ? 'active' : ''}}"><a href="{{ route('userReport') }}" data-toggle="tooltip" data-placement="right" title="Employee Report">Users</a></li> -->
                    <li class="{{(request()->is('report/performance')) ? 'active' : ''}}"><a href="{{ route('performanceReport') }}" data-toggle="tooltip" data-placement="right" title="Employee Performance Report">Performance</a></li>
                    <li class="{{(request()->is('task-bounce-list') || request()->is('task-bounce-report') || request()->is('task-bounce-graph')) ? 'active' : ''}}">
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Task Bounce"> Task Bounce <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li class="{{(request()->is('task-bounce-list')) ? 'active' : ''}}"><a href="{{ route('taskBounce') }}">List</a></li>
                            <li class="{{(request()->is('task-bounce-report')) ? 'active' : ''}}"><a href="{{ route('taskBounceReport') }}">Report</a></li>
                            <li class="{{(request()->is('task-bounce-graph')) ? 'active' : ''}}"><a href="{{ route('taskBounceGraph') }}">Graph</a></li>
                        </ul>
                    </li>
                    @if(!empty(Helper::showDailyStatusReportPage()))
                    <li class="{{(request()->is('daily-status-report')) ? 'active' : ''}}"><a href="{{ route('dailyStatusReport') }}" data-toggle="tooltip" data-placement="right" title="Employee Daily Status Report">Daily Status Report</a></li>
                    @endif
                    <li class="{{(request()->is('report/employee-leave-report')) ? 'active' : ''}}"><a href="{{ route('userLeaveReport') }}" data-toggle="tooltip" data-placement="right" title="Employee Leave Report">Employee Leave Report</a></li>
                    <li class="{{(request()->is('report/project-billability-report')) || (request()->is('report/project-billability-hours-graph')) ? 'active' : ''}}"><a href="{{ route('projectBillabilityReport') }}">Project Billability Report</a></li>
                    <li class="{{(request()->is('report/task-time-report')) ? 'active' : ''}}"><a href="{{ route('taskTimeReport') }}" data-toggle="tooltip" data-placement="right" title="Task Time Report">Task Time Report</a></li>
                </ul>
            </li>
            @endcan

            @can('manage-payroll')
            <li class="{{(request()->is('payroll*') || request()->is('payroll') || request()->is('payroll-user/*') || request()->is('salary-component') || request()->is('salary-hike*')) ? 'active':''}}" >
                <a href="#" data-toggle="tooltip" data-placement="right" title="Payroll"><i class="ri-bill-line"></i> <span class="nav-label"> Payroll</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{(request()->is('payroll') || request()->is('payroll-user/*') || request()->is('payroll*') ) ? 'active' : ''}}"><a href="{{route('payroll.index')}}">Manage Payroll</a></li>
                    <li class="{{(request()->is('salary-component')) ? 'active' : ''}}"><a href="{{route('salary-component.index')}}" data-toggle="tooltip" data-placement="right" title="Salary Components">Salary Components</a></li>
                    <li class="{{ request()->is('salary-hike*') ? 'active' : '' }}"><a href="{{ route('salary-hike.index') }}" data-toggle="tooltip" data-placement="right" title="Salary Hike">Salary Hike</a></li>
                </ul>
            </li>
            @endcan

            @can('view-payslip')
            <li class="{{(request()->is('payslip*')) ? 'active':''}}" >
                <a href="{{route('payslip.index')}}" data-toggle="tooltip" data-placement="right" title="Payslip"><i class="ri-wallet-3-line"></i> <span class="nav-label">Payslip</span></a>
            </li>
            @endcan


            @canany(['users', 'view-clients'])
            <li class="{{ (request()->is('users')) || (request()->is('clients')) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Users">
                <i class="ri-group-line"></i> <span class="nav-label">Users</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    @can('view-users')
                    <li class="{{(request()->is('users')) ? 'active' : ''}}">
                        <a href="{{route('users.index')}}" data-toggle="tooltip" data-placement="right" title="Users">
                        Users
                        </a>
                    </li>
                    @endcan
                    @can('view-clients')
                    <li class="{{(request()->is('clients')) ? 'active' : ''}}">
                        <a href="{{route('clients.index')}}" data-toggle="tooltip" data-placement="right" title="Clients">
                        Clients
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['view-holidays', 'view-newsletters'])
            <li class="{{(request()->is('newsletters')||request()->is('manage-holidays')||request()->is('alerts') || request()->is('recruitments') || request()->is('recruitment-schedules')) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Miscellaneous">
                <i class="ri-function-line"></i>
                    <span class="nav-label">Miscellaneous</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    @can('view-holidays')
                    <li class="{{(request()->is('manage-holidays')) ? 'active' : ''}}">
                        <a href="{{ route('manage-holidays.index') }}" data-toggle="tooltip" data-placement="right" title="Holidays">
                            Holidays
                        </a>
                    </li>
                    @endcan
                    @can('view-newsletters')
                    <li class="{{(request()->is('newsletters')) ? 'active' : ''}}">
                        <a href="{{ route('newsletters.index') }}" data-toggle="tooltip" data-placement="right" title="Newsletters">
                            Newsletters
                        </a>
                    </li>
                    @endcan
                    @can('manage-alerts')
                    <li class="{{(request()->is('alerts')) ? 'active' : ''}}">
                        <a href="{{ route('alerts.index') }}" data-toggle="tooltip" data-placement="right" title="Manage Popup Alerts">
                            Manage Popup Alerts
                        </a>
                    </li>
                    @endcan
                    @can('manage-recruitments')
                    <li class="{{(request()->is('recruitments')) ? 'active' : ''}}">
                        <a href="{{route('recruitments.index')}}" data-toggle="tooltip" data-placement="right" title="Recruitments">
                            Recruitments
                        </a>
                    </li>
                    <li class="{{(request()->is('recruitment-schedules')) ? 'active' : ''}}">
                        <a href="{{route('listSchedules')}}" data-toggle="tooltip" data-placement="right" title="Recruitment Schedules">
                            Recruitment Schedules
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['manage-assets','view-assets'])
            <li class="{{(request()->is('employee-asset-list') || request()->is('employee-ticket-raised-assets') || request()->is('ticket-raised-assets') || request()->is('assets') || request()->is('assets/*') || request()->is('asset-search') || request()->is('ticket-raised-asset-search') || request()->is('employee-ticket-raised-asset-search') || request()->is('asset-types') || request()->is('asset-vendors') || request()->is('ticket-status') || request()->is('attributes')) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Assets">
                    <i class="ri-home-line"></i>
                    <span class="nav-label">IT Assets</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    @can('manage-assets')
                    <li class="{{(request()->is('assets') || request()->is('assets/*') || request()->is('asset-search')) ? 'active' : ''}}">
                        <a href="{{route('assets.index')}}" data-toggle="tooltip" data-placement="right" title="Assets">Assets
                        </a>
                    </li>
                    <li class="{{(request()->is('asset-types') || request()->is('attributes')) ? 'active' : ''}}">
                        <a href="{{route('asset-types.index')}}" data-toggle="tooltip" data-placement="right" title="Types">Types
                        </a>
                    </li>
                    <li class="{{(request()->is('asset-vendors')) ? 'active' : ''}}">
                        <a href="{{route('asset-vendors.index')}}" data-toggle="tooltip" data-placement="right" title="Vendors">Vendors
                        </a>
                    </li>
                    <li class="{{(request()->is('ticket-raised-assets') || request()->is('ticket-raised-asset-search')) ? 'active' : ''}}">
                        <a href="{{route('assets.ticketRaisedAssetList')}}" data-toggle="tooltip" data-placement="right" title="Tickets">Tickets
                        </a>
                    </li>
                    <li class="{{(request()->is('ticket-status')) ? 'active' : ''}}">
                        <a href="{{route('ticket-status.index')}}" data-toggle="tooltip" data-placement="right" title="Ticket Status">Ticket Status
                        </a>
                    </li>
                    @endcan
                    @unlessrole('administrator|client|consultant')
                    @can('view-assets')
                    <li class="{{(request()->is('employee-asset-list')) ? 'active' : ''}}">
                        <a href="{{route('assets.employeeAssetList')}}" data-toggle="tooltip" data-placement="right" title="My Assets">My Assets
                        </a>
                    </li>
                    <li class="{{(request()->is('employee-ticket-raised-assets') || request()->is('employee-ticket-raised-asset-search')) ? 'active' : ''}}">
                        <a href="{{route('assets.employeeTicketRaisedAssetList')}}" data-toggle="tooltip" data-placement="right" title="My Tickets">My Tickets
                        </a>
                    </li>
                    @endcan
                    @endunlessrole
                </ul>
            </li>
            @endcanany

            @unlessrole('client|consultant')
            @canany(['apply-leave', 'manage-leave'])
            <li class="{{ (request()->is('apply-leave')) || (request()->is('pending-leave-applications')||request()->is('previous-leave-applications')||request()->is('assign-leave')||request()->is('compensations')||(request()->is('compensatory-applications'))) ? 'active' : ''}}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Leaves">
                <i class="ri-file-add-line"></i> <span class="nav-label">Leaves</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    @can('apply-leave')
                    <li class="{{(request()->is('apply-leave')) || (request()->is('compensations')) ? 'active' : ''}}">
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Apply">
                            Apply
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li class="{{(request()->is('apply-leave')) ? 'active' : ''}}">
                                <a href="{{ route('apply-leave.index') }}" data-toggle="tooltip" data-placement="right" title="Apply Leave">
                                    Apply Leave
                                </a>
                            </li>
                            <li class="{{(request()->is('compensations')) ? 'active' : ''}}">
                                <a href="{{ route('compensations.index') }}" data-toggle="tooltip" data-placement="right" title="Apply Compensatory">
                                    Apply Compensatory Off
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('manage-leave')
                    <li class="{{(request()->is('pending-leave-applications')) ? 'active' : ''}}">
                        <a href="{{ route('adminIndex') }}" data-toggle="tooltip" data-placement="right" title="Pending Applications">Pending Applications</a>
                    </li>
                    <li class="{{(request()->is('previous-leave-applications')) ? 'active' : ''}}">
                        <a href="{{ route('previousApplications') }}" data-toggle="tooltip" data-placement="right" title="Approved Applications">Approved Applications</a>
                    </li>
                    <li class="{{(request()->is('assign-leave')) ? 'active' : ''}}">
                        <a href="{{ route('assignLeave') }}" data-toggle="tooltip" data-placement="right" title="Assign Leave">Assign Leave</a>
                    </li>
                    <li class="{{(request()->is('compensatory-applications')) ? 'active' : ''}}">
                        <a href="{{ route('compensatoryApplications') }}" data-toggle="tooltip" data-placement="right" title="Compensatory Applications">Compensatory Applications</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @endunlessrole

            @canany(['view-user-access-levels','view-overheads','view-fixed-overheads','manage-settings'])
            <li class="{{ (request()->is('company-info') || request()->is('project-technologies') || request()->is('user-access-levels') || request()->is('overheads') || request()->is('session-types') || request()->is('status-types') || request()->is('fixed-overhead') || request()->is('base-currency') || request()->is('reports-settings') || request()->is('project-settings') || request()->is('projects-settings'))  ? 'active' : '' }}">
                <a href="#" data-toggle="tooltip" data-placement="right" title="Settings"><i class="ri-settings-5-line"></i> <span class="nav-label">Settings</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @can(['manage-settings'])
                    <li class="{{(request()->is('company-info')) ? 'active' : ''}}"><a href="{{ route('company-info') }}" data-toggle="tooltip" data-placement="right" title="Company Information">Company Information</a></li>
                    <li class="{{(request()->is('project-technologies')) ? 'active' : ''}}"><a href="{{ route('project-technologies.index') }}" data-toggle="tooltip" data-placement="right" title="Project Technologies">Project Technologies</a></li>
                    @endcan
                    @canany(['view-overheads', 'view-fixed-overheads'])
                    <li class="{{(request()->is('overheads')|| request()->is('fixed-overhead')) ? 'active' : ''}}"><a href="{{ route('overheads.index') }}" data-toggle="tooltip" data-placement="right" title="Overheads">Overheads & Expenses</a>
                        <ul class="nav nav-third-level">
                            @can(['view-overheads'])
                            <li class="{{(request()->is('overheads')) ? 'active' : ''}}"><a href="{{ route('overheads.index') }}" data-toggle="tooltip" data-placement="right" title="Overheads & Expenses">Manage Expenses</a></li>
                            @endcan
                            @can(['view-fixed-overheads'])
                            <li class="{{(request()->is('fixed-overhead')) ? 'active' : ''}}"><a href="{{route('fixed-overhead.index')}}" data-toggle="tooltip" data-placement="right" title="Manage Overheads"> Fixed Overheads</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    @can(['view-user-access-levels'])
                    <li class="{{(request()->is('user-access-levels')) ? 'active' : ''}}"><a href="{{route('user-access-levels')}}" data-toggle="tooltip" data-placement="right" title="User Access Levels">User Access Levels</a></li>
                    @endcan
                    @can(['manage-settings'])
                    <li class="{{(request()->is('session-types')) ? 'active' : ''}}"><a href="{{route('session-types.index')}}" data-toggle="tooltip" data-placement="right" title="Session Types">Session Types</a></li>
                    <li class="{{(request()->is('base-currency')) ? 'active' : ''}}"><a href="{{route('base-currency.index')}}" data-toggle="tooltip" data-placement="right" title="Base Currency">Base Currency</a></li>
                    <li class="{{(request()->is('reports-settings')) ? 'active' : ''}}"><a href="{{route('reports.index')}}" data-toggle="tooltip" data-placement="right" title="Report Settings">Report Settings</a></li>
                    <li class="{{(request()->is('projects-settings')) ? 'active' : ''}}"><a href="{{route('project.index')}}" data-toggle="tooltip" data-placement="right" title="Project Settings">Project Settings</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @if(config('general.santa.enabled'))
                @hasanyrole('hr-manager|hr-associate')
                <li class="{{(request()->is('santa-members')) ? 'active' : ''}}">
                    <a href="{{ route('santa-members.index') }}" data-toggle="tooltip" data-placement="right" title="Santa Members"><i class="fa fa-tree" aria-hidden="true"></i> <span class="nav-label">Santa Members</span></span></a>
                </li>
                @endhasanyrole

                @unlessrole('client|consultant')
                        <li class="{{(request()->is('find-my-santa')) ? 'active' : ''}}">
                            <a href="{{ route('find-my-santa') }}" data-toggle="tooltip" data-placement="right" title="Secret Santa"><i class="fa fa-tree" aria-hidden="true"></i> <span class="nav-label">Secret Santa</span></span></a>
                        </li>
                @endunlessrole
            @endif
        </ul>
    </div>
</nav>
<!-- end -->
