<?php

return [

    'issue-records' => [
        'types' => [
            ['title' => 'Backend', 'slug' => 'backend'],
            ['title' => 'FrontEnd', 'slug' => 'frontend'],
            ['title' => 'AWS', 'slug' => 'aws'],
            ['title' => 'Server Related', 'slug' => 'server-related'],
            ['title' => 'Network', 'slug' => 'network'],
            ['title' => 'Database', 'slug' => 'database'],
            ['title' => 'Git', 'slug' => 'git'],
            ['title' => 'Composer', 'slug' => 'composer'],
            ['title' => 'Linux', 'slug' => 'linux'],
            ['title' => 'Css', 'slug' => 'css'],
            ['title' => 'NPM', 'slug' => 'npm'],
        ]
    ],

    'leaves' => [
        'leave-session' => [
            'Full Day',
            'First Half',
            'Second Half'
        ],
        'leave-type' => [
            'Casual',
            'Medical',
            'Compensatory off',
            'LOP',
            'Paternity'
        ]
    ],

    'permissions' => [
        'View User Access Levels', 'Manage User Access Levels', 'Manage Roles', 'Manage Permissions', 'View Overheads', 'View Holidays', 'View Fixed Overheads', 'View Projects', 'Manage Projects', 'View Tasks', 'Manage Tasks', 'View Project Credentials', 'Manage Project Credentials', 'View Project Documents', 'Manage Project Documents', 'View Admin Dashboard', 'View My Timesheet', 'Apply Leave', 'Manage Leave', 'View Reports', 'Manage Timesheets', 'View Users', 'Manage Users', 'View Clients', 'Manage Clients', 'Manage Overheads', 'Manage Holidays', 'View Task Bounce Report', 'View Dashboard', 'Manage Issue Records', 'Access My Credentials', 'Access My Tasks', 'View Newsletters', 'Manage Newsletters', 'Manage DSR', 'Manage Daily Checklists',
        'View Status Graph', 'View Profit Graph', 'Manage Alerts', 'Manage Recruitments', 'Manage Payroll', 'View QA Feedback', 'View Project Cost'
    ],

    'project' => [
        'status' => ['Active', 'Closed', 'Cancelled']
    ],

    'qa-feedback' => [
        'level-of-severity' => [
            2 => 'Low',
            4 => 'Medium',
            6 => 'High',
            10 => 'Critical'
        ]
    ],

    'recruitment' => [
        'category' => ['Front End Developer', 'PHP Developer', 'React Native Developer', 'QA', 'Fresher'],
        'source' => ['Naukri', 'Linkedin', 'Agency', 'Email', 'Reference', 'Others'],
        'status' => ['Pending', 'Processing', 'Selected', 'Rejected', 'On Hold', 'Test Not Attended', 'Interview Not Attended', 'Can be Considered', 'Declined Offer']
    ],

    'roles' => [
        'Administrator',
        'Project Manager',
        'Employee',
        'Client',
        'HR Manager',
        'Team Lead',
        'Consultant',
    ],

    'tasks' => [
        'task-status' => [
            'backlog' => 'Backlog',
            'progress' => 'In Progress',
            'qa' => 'Under QA',
            'development-completed' => 'Development Completed',
            'hold' => 'On Hold',
            'awaiting-client' => 'Awaiting Client',
            'client-review' => 'Client Review',
            'done' => 'Done',
            'completed' => 'Completed',
        ]
    ],

    'taxonomy' => [
        'heads' => [
            'Checklist',
            'gstin',
            'Cin',
            'Tax Rate',
            'Account Details',
            'Base Currency',
            'Guideline Tags'
        ],
    ],

    'wish' => [
        'type' => ['Message', 'Text', 'Wish']
    ],

    'user' => [
        'permissions' => [
            'administrator' => [
                'view-user-access-levels', 'manage-user-access-levels', 'manage-roles', 'manage-permissions', 'view-overheads', 'view-holidays', 'view-fixed-overheads', 'view-projects', 'manage-projects', 'view-tasks', 'manage-tasks', 'view-project-credentials', 'manage-project-credentials', 'view-project-documents', 'manage-project-documents', 'view-admin-dashboard', 'view-my-timesheet', 'apply-leave', 'manage-leave', 'view-reports', 'manage-timesheets', 'view-users', 'manage-users', 'view-clients', 'manage-clients', 'manage-overheads', 'manage-holidays', 'view-task-bounce-report', 'view-dashboard', 'manage-issue-records', 'access-my-credentials', 'access-my-tasks', 'view-newsletters', 'manage-newsletters', 'manage-dsr', 'manage-daily-checklists', 'view-status-graph', 'view-profit-graph', 'manage-alerts', 'manage-recruitments', 'view-project-cost'
            ],
            'employee' => [
                'view-holidays', 'view-projects', 'view-my-timesheet', 'view-dashboard', 'manage-issue-records', 'access-my-credentials',  'access-my-tasks', 'view-newsletters', 'apply-leave', 'manage-daily-checklists',
            ],
            'project-manager' => [
                'view-holidays', 'view-projects', 'manage-projects', 'view-tasks', 'manage-tasks', 'view-project-credentials', 'manage-project-credentials', 'view-project-documents', 'manage-project-documents', 'view-admin-dashboard', 'view-my-timesheet', 'apply-leave', 'view-reports', 'manage-timesheets', 'view-dashboard', 'manage-issue-records', 'access-my-credentials', 'access-my-tasks', 'manage-daily-checklists', 'view-status-graph'
            ],
            'client' => [
                'view-dashboard', 'access-my-tasks', 'view-tasks', 'view-projects'
            ],
            'hr-manager' => [
                'view-holidays', 'view-my-timesheet', 'apply-leave', 'manage-leave', 'view-users', 'manage-users', 'view-clients', 'manage-clients', 'manage-holidays', 'view-dashboard', 'manage-issue-records', 'access-my-credentials', 'access-my-tasks', 'manage-daily-checklists', 'manage-alerts', 'manage-recruitments', 'manage-payroll'
            ],
            'team-lead' => ['view-dashboard', 'view-holidays', 'access-my-tasks', 'access-my-credentials', 'view-projects', 'manage-issue-records', 'manage-projects', 'view-tasks', 'manage-tasks', 'view-project-credentials', 'manage-project-credentials', 'view-project-documents', 'manage-project-documents', 'view-admin-dashboard', 'view-my-timesheet', 'apply-leave', 'view-reports', 'manage-timesheets'],
            'consultant' => ['view-dashboard']
        ]
    ],
    'expense-type' => [
        'Subscriptions',
        'Team bounding',
        'Operational',
        'Maintenance'
    ]
];
