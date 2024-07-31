
<?php

return [
    'min-session-hour-per-day' => 8,
    'days-limit-bulk-done-status' => 15,
    'santa' => [
        'enabled' => env('SECRET_SANTA_ENABLED', false),
    ],
    'task' => [
        'task_session' => [
            'default_session_type' => 'development',
            'default_task_pause_message' => 'Task Paused'
        ],
        'session' => [
            'start' => 'started',
            'pause' => 'pause',
            'resume' => 'resume',
            'stop' => 'over'
        ],
    ],
    'cron-mail' => [
        'no-timesheet-in-two-days' => [
            'excluded-departments' => ['Business Development', 'Project Co-ordinator']
        ],
        'log' => [
            'mail' => 'nithin@2hatslogic.com'
        ]
    ],
    'timesheets' => [
        'user-type' => [
            "labels" => [
                0 => "All",
                1 => "On Contract",
                2 => "Not On Contract"
            ]
        ]
    ],
    'social-media-links' => [
        'linked-in' => 'https://www.linkedin.com/company/2hats-logic/',
        'instagram' => 'https://www.instagram.com/2HatsLogic/',
        'twitter' => 'https://twitter.com/2HatsLogic',
        'facebook' => 'https://www.facebook.com/2hatslogic'
    ],
    'leaves' => [
        'automated-leaves' => [
            'type' => 'LOP',
            'session' => 'Full Day',
            'lop' => "Yes",
            'reason' => 'Failed to enter session hours',
            'status' => 'Approved',
        ]
    ],
    'recruitments' => [
        'pagination' => 15,
    ],
    'footer' => [
        'logo' => '/images/Coherence-logo.png',
        'link' => '',
    ],

    'images' => [
        'arrow-right' => '/images/arrow-right.svg',
        'sticky-note' => '/images/sticky-note.svg',
    ],
    'pagination' => 25,
    'currency-api-cache-time' => 36400,
    'email' => [
        'creator-notification-user-role' => 'project-manager',
    ],
    'assets' => [
        'status' => [
            'has-users' => ['allocated', 'ticket_raised']
        ]
    ],
    'task_actual_estimate' => [
        'view_roles' =>['administrator', 'project-manager']
    ],
    'project_billability_percentage_cutoff' => 75,
    'user-type' => [
        "labels" => [
            2 => "All",
            1 => "On Contract",
            0 => "Not On Contract"
        ]
    ],
    'progress_graph_view_cutoffs' => [
        // The numbers are the count of days
        'year' => 730,  // If more than 2 years you can only view year wise graph
        'month' => 124, // If more than 4 Month you can only view year, month wise graph
        'week' => 31 // If more than a Month , day view will not be available
    ],
    'user-code' => [
        'prefix' => '2HL',
        'start-series' => '0101',
    ],
];
