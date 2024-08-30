<script setup lang="ts">
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { usePermissions } from "@/composables/usePermissions";
import { SiteSettings } from "@/types/site-settings-types";
const { can, hasRole, hasAnyRole, hasAllRoles, hasAllPermissions, hasAnyPermission } = usePermissions();

const page = usePage();
const siteSettings = computed(() => page.props.site_settings as SiteSettings);
</script>

<template>
    <nav class="navbar-default navbar-static-side" role="navigation">
        <a class="navbar-minimalize minimalize-styl-2 btn toggle-nav-user" href="#">
            <i class="fa fa-angle-left"></i>
        </a>
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="profile-element">
                        <span class="avatar">
                            <img id="header_pic" alt="logo" class="img-circle" :src="siteSettings.company_logo"
                                style="width: 50px" />
                        </span>
                        <span class="user-basic">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"></span>
                                <span class="block">
                                    <strong class="font-bold">Name dynamic</strong>
                                </span>
                                <span class="small text-muted text-xs block desc">Designation dynamic</span>
                            </a>
                        </span>
                    </div>
                    <!-- mobile view -->
                    <div class="logo-element">
                        <a href="">
                            <img id="header_pic" alt="logo" class="img-circle" :src="siteSettings.company_logo" />
                        </a>
                    </div>
                </li>
                <li :class="{ 'active': route().current('dashboard') }" v-if="can('view-dashboard')">
                    <Link :href="route('dashboard')" data-toggle="tooltip" data-placement="right" title="Dashboard">
                    <i class="ri-dashboard-line"></i>
                    <span class="nav-label">Dashboard</span>
                    </Link>
                </li>
                <li :class="{ 'active': route().current('projects.*') || route().current('archivedProjects') || route().current('archivedProjectSearch') }"
                    v-if="can('view-projects')">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Projects">
                        <i class="ri-folder-3-line"></i>
                        <span class="nav-label">Projects</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('projects.index') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Active Projects">
                                Active Projects
                            </a>
                        </li>
                        <li
                            :class="{ 'active': route().current('archivedProjects') || route().current('archivedProjectSearch') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Archived Tasks">
                                Archived Projects
                            </a>
                        </li>
                    </ul>
                </li>

                <li :class="{ 'active': route().current('tasks.index') || route().current('ongoing-tasks.index') || route().current('upcomingTasks') || route().current('completedTasks') || route().current('archivedTasks') || route().current('archivedTaskSearch') || route().current('tasks.*') }"
                    v-if="!hasRole('client') && hasAnyPermission(['view-tasks', 'manage-tasks', 'access-my-tasks'])">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="My Task">
                        <i class="ri-file-2-line"></i>
                        <span class="nav-label">Tasks</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('tasks.index') }" v-if="can('view-tasks')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Active Tasks">
                                Active Tasks
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('ongoing-tasks.index') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Ongoing Tasks">My Ongoing
                                Tasks</a>
                        </li>
                        <li :class="{ 'active': route().current('upcomingTasks') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Upcoming Tasks">My Upcoming
                                Tasks</a>
                        </li>
                        <li :class="{ 'active': route().current('completedTasks') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Completed Tasks">My Completed
                                Tasks</a>
                        </li>
                        <li :class="{ 'active': route().current('archivedTaskSearch') || route().current('archivedTasks') }"
                            v-if="can('manage-tasks')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Archived Tasks">
                                Archived Tasks
                            </a>
                        </li>
                    </ul>
                </li>

                <li :class="{ 'active': route().current('tasks.index') }"
                    v-else-if="hasRole('client') && hasAnyPermission(['view-tasks', 'manage-tasks'])">

                    <a href="#" data-toggle="tooltip" data-placement="right" title="My Task">
                        <i class="ri-file-2-line"></i>
                        <span class="nav-label">Tasks</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('tasks.index') }" v-if="can('view-tasks')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Active Tasks">
                                Active Tasks
                            </a>
                        </li>
                    </ul>
                </li>

                <li :class="{ 'active': route().current('useChecklists') || route().current('checklists.index') || route().current('checklistReport') }"
                    v-if="can('manage-daily-checklists')">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Checklists">
                        <i class="ri-list-check-3"></i>
                        <span class="nav-label">Checklists</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('useChecklists') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Update Checklists">
                                Checklists
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('checklists.index') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Manage Checklists">Manage
                                Checklists</a>
                        </li>
                        <li :class="{ 'active': route().current('checklistReport') }">
                            <a href="" data-toggle="tooltip" data-placement="right"
                                title="Daily checklist Reports">Reports</a>
                        </li>
                    </ul>
                </li>

                <li :class="{ 'active': route().current('my-timesheet.index') || route().current('viewSheetUser') || route().current('userDaterange') || route().current('viewSheetProject') || route().current('projectDaterange') || route().current('viewSheetClient') || route().current('clientDaterange') || route().current('newTimeSheet') || route().current('my-team-timesheet.index') || route().current('team-timesheet-search') }"
                    v-if="hasAnyPermission(['manage-timesheets', 'view-my-timesheet', 'view-my-team'])">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Timesheet">
                        <i class="ri-time-line"></i>
                        <span class="nav-label">Timesheets</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('my-timesheet.index') }"
                            v-if="can('view-my-timesheet')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="My Timesheet">
                                My Timesheet</a>
                        </li>
                        <li :class="{ 'active': route().current('my-team-timesheet.index') || route().current('team-timesheet-search') }"
                            v-if="can('manage-team')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="My Team">
                                My Team</a>
                        </li>
                        <li :class="{ 'active': route().current('newTimeSheet') }" v-if="can('manage-timesheets')">
                            <a href="/get-timesheets" data-toggle="tooltip" data-placement="right"
                                title="timesheets">Timesheets</a>
                        </li>
                    </ul>
                </li>

                <li :class="{ 'active': route().current('work-notes.index') || route().current('issue-records.*') || route().current('guidelines.index') || route().current('credentials.index') || route().current('easyAccess') || route().current('qa-feedback.index') || route().current('my-credentials.index') || route().current('guidelines.*') }"
                    v-if="!hasRole('client')">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Tools">
                        <i class="ri-tools-line"></i>
                        <span class="nav-label">Tools</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('work-notes.index') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Work Notes">
                                Work Notes
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('issue-records.*') }"
                            v-if="can('manage-issue-records')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Issue Records">Issue Records
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('credentials.index') }"
                            v-if="hasAnyPermission(['view-project-credentials', 'manage-project-credentials'])">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Project Credentials">
                                Project Credentials
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('my-credentials.index') }"
                            v-if="can('access-my-credentials')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="My Credentials">
                                My Credentials
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('qa-feedback.index') }" v-if="can('view-qa-feedback')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="QA Feedback">
                                QA Feedback
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('easyAccess') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Easy Access">
                                Easy Access
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('emailSignature') }"
                            v-if="can('access-email-signature')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Email Signature"
                                target="_blank">
                                Email Signature
                            </a>
                        </li>
                        <li :class="{ 'active': route().current('iguidelines.index') }" v-if="can('manage-guidelines')">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Guidelines">
                                Guidelines
                            </a>
                        </li>
                    </ul>
                </li>

                <li :class="{ 'active': route().current('clientReport') || route().current('projectReport') || route().current('userReport') || route().current('performanceReport') || route().current('taskBounce') || route().current('taskBounceReport') || route().current('taskBounceGraph') || route().current('dailyStatusReport') || route().current('userLeaveReport') || route().current('ProjectCost') || route().current('taskTimeReport') || route().current('projectBillabilityReport') || route().current('projectBillabilityHoursGraph') }"
                    v-if="can('view-reports')">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Report">
                        <i class="fa fa-files-o" aria-hidden="true"></i>
                        <span class="nav-label">Reports</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li :class="{ 'active': route().current('performanceReport') }">
                            <a href="" data-toggle="tooltip" data-placement="right"
                                title="Employee Performance Report">Performance</a>
                        </li>
                        <li
                            :class="{ 'active': route().current('taskBounce') || route().current('taskBounceReport') || route().current('taskBounceGraph') }">
                            <a href="#" data-toggle="tooltip" data-placement="right" title="Task Bounce">
                                Task Bounce <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li :class="{ 'active': route().current('taskBounce') }">
                                    <a href="">List</a>
                                </li>
                                <li :class="{ 'active': route().current('taskBounceReport') }">
                                    <a href="">Report</a>
                                </li>
                                <li :class="{ 'active': route().current('taskBounceGraph') }">
                                    <a href="">Graph</a>
                                </li>
                            </ul>
                        </li>
                        <!-- TODO::check this -->
                        <!-- @if(!empty(Helper::showDailyStatusReportPage())) -->
                        <li :class="{ 'active': route().current('dailyStatusReport') }">
                            <a href="" data-toggle="tooltip" data-placement="right"
                                title="Employee Daily Status Report">Daily Status Report</a>
                        </li>
                        <!-- @endif -->
                        <li :class="{ 'active': route().current('userLeaveReport') }">
                            <a href="" data-toggle="tooltip" data-placement="right"
                                title="Employee Leave Report">Employee Leave Report</a>
                        </li>
                        <li
                            :class="{ 'active': route().current('projectBillabilityReport') || route().current('projectBillabilityHoursGraph') }">
                            <a href="">Project Billability Report</a>
                        </li>
                        <li :class="{ 'active': route().current('taskTimeReport') }">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Task Time Report">Task Time
                                Report</a>
                        </li>
                    </ul>
                </li>

                <!-- @can('manage-payroll') -->
                <li class="">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Payroll">
                        <i class="ri-bill-line"></i>
                        <span class="nav-label"> Payroll</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="">
                            <a href="">Manage Payroll</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Salary Components">Salary
                                Components</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Salary Hike">Salary Hike</a>
                        </li>
                    </ul>
                </li>
                <!-- @endcan -->

                <!-- @can('view-payslip') -->
                <li class="">
                    <a href="" data-toggle="tooltip" data-placement="right" title="Payslip">
                        <i class="ri-wallet-3-line"></i>
                        <span class="nav-label">Payslip</span>
                    </a>
                </li>
                <!-- @endcan -->

                <!-- @canany(['users', 'view-clients']) -->
                <li class="">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Users">
                        <i class="ri-group-line"></i>
                        <span class="nav-label">Users</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <!-- @can('view-users') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Users">
                                Users
                            </a>
                        </li>
                        <!-- @endcan -->
                        <!-- @can('view-clients') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Clients">
                                Clients
                            </a>
                        </li>
                        <!-- @endcan -->
                    </ul>
                </li>
                <!-- @endcanany -->

                <!-- @canany(['view-holidays', 'view-newsletters']) -->
                <li class="">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Miscellaneous">
                        <i class="ri-function-line"></i>
                        <span class="nav-label">Miscellaneous</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <!-- @can('view-holidays') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Holidays">
                                Holidays
                            </a>
                        </li>
                        <!-- @endcan -->
                        <!-- @can('view-newsletters') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Newsletters">
                                Newsletters
                            </a>
                        </li>
                        <!-- @endcan -->
                        <!-- @can('manage-alerts') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Manage Popup Alerts">
                                Manage Popup Alerts
                            </a>
                        </li>
                        <!-- @endcan -->
                        <!-- @can('manage-recruitments') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Recruitments">
                                Recruitments
                            </a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Recruitment Schedules">
                                Recruitment Schedules
                            </a>
                        </li>
                        <!-- @endcan -->
                    </ul>
                </li>
                <!-- @endcanany -->

                <!-- @canany(['manage-assets','view-assets']) -->
                <li class="">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Assets">
                        <i class="ri-home-line"></i>
                        <span class="nav-label">IT Assets</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        <!-- @can('manage-assets') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Assets">Assets
                            </a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Types">Types
                            </a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Vendors">Vendors
                            </a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Tickets">Tickets
                            </a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Ticket Status">Ticket Status
                            </a>
                        </li>
                        <!-- @endcan -->
                        <!-- @unlessrole('administrator|client|consultant') -->
                        <!-- @can('view-assets') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="My Assets">My Assets
                            </a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="My Tickets">My Tickets
                            </a>
                        </li>
                        <!-- @endcan -->
                        <!-- @endunlessrole -->
                    </ul>
                </li>
                <!-- @endcanany -->

                <!-- @unlessrole('client|consultant') -->
                <!-- @canany(['apply-leave', 'manage-leave']) -->
                <li class="">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Leaves">
                        <i class="ri-file-add-line"></i>
                        <span class="nav-label">Leaves</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <!-- @can('apply-leave') -->
                        <li class="">
                            <a href="#" data-toggle="tooltip" data-placement="right" title="Apply">
                                Apply
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li class="">
                                    <a href="" data-toggle="tooltip" data-placement="right" title="Apply Leave">
                                        Apply Leave
                                    </a>
                                </li>
                                <li class="">
                                    <a href="" data-toggle="tooltip" data-placement="right" title="Apply Compensatory">
                                        Apply Compensatory Off
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- @endcan -->
                        <!-- @can('manage-leave') -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Pending Applications">Pending
                                Applications</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right"
                                title="Approved Applications">Approved Applications</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Assign Leave">Assign
                                Leave</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right"
                                title="Compensatory Applications">Compensatory Applications</a>
                        </li>
                        <!-- @endcan -->
                    </ul>
                </li>
                <!-- @endcanany -->
                <!-- @endunlessrole -->

                <!-- @canany(['view-user-access-levels','view-overheads','view-fixed-overheads','manage-settings']) -->
                <li class="">
                    <a href="#" data-toggle="tooltip" data-placement="right" title="Settings">
                        <i class="ri-settings-5-line"></i>
                        <span class="nav-label">Settings</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <!-- @can(['manage-settings']) -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Company Information">Company
                                Information</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Project Technologies">Project
                                Technologies</a>
                        </li>
                        <!-- @endcan -->
                        <!-- @canany(['view-overheads', 'view-fixed-overheads']) -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Overheads">Overheads &
                                Expenses</a>
                            <ul class="nav nav-third-level">
                                <!-- @can(['view-overheads']) -->
                                <li class="">
                                    <a href="" data-toggle="tooltip" data-placement="right"
                                        title="Overheads & Expenses">Manage Expenses</a>
                                </li>
                                <!-- @endcan -->
                                <!-- @can(['view-fixed-overheads']) -->
                                <li class="">
                                    <a href="" data-toggle="tooltip" data-placement="right" title="Manage Overheads">
                                        Fixed Overheads</a>
                                </li>
                            </ul>
                        </li>
                        <!-- @endcanany -->
                        <!-- @can(['view-user-access-levels']) -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="User Access Levels">User
                                Access Levels</a>
                        </li>
                        <!-- @endcan -->
                        <!-- @can(['manage-settings']) -->
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Session Types">Session
                                Types</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Base Currency">Base
                                Currency</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Report Settings">Report
                                Settings</a>
                        </li>
                        <li class="">
                            <a href="" data-toggle="tooltip" data-placement="right" title="Project Settings">Project
                                Settings</a>
                        </li>
                        <!-- @endcan -->
                    </ul>
                </li>
                <!-- @endcanany -->
                <!-- @if(config('general.santa.enabled')) -->
                <!-- @hasanyrole('hr-manager|hr-associate') -->
                <li class="">
                    <a href="" data-toggle="tooltip" data-placement="right" title="Santa Members">
                        <i class="fa fa-tree" aria-hidden="true"></i>
                        <span class="nav-label">Santa Members</span>
                    </a>
                </li>
                <!-- @endhasanyrole -->

                <!-- @unlessrole('client|consultant') -->
                <li class="">
                    <a href="" data-toggle="tooltip" data-placement="right" title="Secret Santa">
                        <i class="fa fa-tree" aria-hidden="true"></i>
                        <span class="nav-label">Secret Santa</span>
                    </a>
                </li>
                <!-- @endunlessrole -->
                <!-- @endif -->
            </ul>
        </div>
    </nav>
</template>