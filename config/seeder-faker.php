<?php

return [
    'settings' => [
        'config' => [
            'company_name' => [
                'label' => 'Company Name',
                'slug' => 'company_name',
                'value' => 'ASDR Company Private Limited',
            ],
            'company_address_line1' => [
                'label' => 'Address',
                'slug' => 'company_address_line1',
                'value' => 'Hendrik Willem Mesdagstraat 108',
            ],
            'company_address_line2' => [
                'label' => 'Address',
                'slug' => 'company_address_line2',
                'value' => 'Center V',
            ],
            'company_city' => [
                'label' => 'City',
                'slug' => 'company_city',
                'value' => 'Drenthe',
            ],
            'company_state' => [
                'label' => 'State',
                'slug' => 'company_state',
                'value' => 'Groningen',
            ],
            'company_zip' => [
                'label' => 'Zip/Postal code',
                'slug' => 'company_zip',
                'value' => ' 7471 AE',
            ],

            'company_country' => [
                'label' => 'Country',
                'slug' => 'company_country',
                'value' => 'Netherlands',
            ],
            'company_email' => [
                'label' => 'Email',
                'slug' => 'company_email',
                'value' => 'info@asdr.com',
            ],
            'company_phone' => [
                'label' => 'Phone',
                'slug' => 'company_phone',
                'value' => ' 091851693147',
            ],
            'company_cin' => [
                'label' => 'CIN',
                'slug' => 'company_cin',
                'value' => 'HNB584DF587154DFG',
            ],
            'company_gstin' => [
                'label' => 'GSTIN',
                'slug' => 'company_gstin',
                'value' => '48e1g4rt511d6e8',
            ],
            'company_bankaccount_details' => [
                'label' => 'Account Details',
                'slug' => 'company_bankaccount_details',
                'value' => '<p>bank Name: ERTF Bank</p><p><br>Account # : 4895414634</p><p><br>Name :ASDR Company Private Limited</p><p><br>Branch : NEST branch</p><p><br>swift code : ASDR78124</p><p><br>IFSE code : ASDR15795</p>',
            ],
            'company_tax_rate' => [
                'label' => 'Tax Rate',
                'slug' => 'company_tax_rate',
                'value' => '20',
            ],
            'company_logo' => [
                'label' => 'Logo',
                'slug' => 'company_logo',
                'value' => '',
            ],
            'company_financial_year_from' => [
                'label' => 'Financial Year From',
                'slug' => 'company_financial_year_from',
                'value' => '01/04',
            ],
            'company_financial_year_to' => [
                'label' => 'Financial Year To',
                'slug' => 'company_financial_year_to',
                'value' => '31/03',
            ],
            'email_config_employee_hour_email_recipients' => [
                'label' => 'Employee Hour Tracker Email Recipients',
                'slug' => 'email_config_employee_hour_email_recipients',
                'value' => null,
            ],
            'email_config_weekly_low_hours_email_recipients' => [
                'label' => 'Below 35 hours Email',
                'slug' => 'email_config_weekly_low_hours_email_recipients',
                'value' => null,
            ],
            'email_config_weekly_report_cron_day' => [
                'label' => 'Weekly report runs on',
                'slug' => 'email_config_weekly_report_cron_day',
                'value' => null,
            ],
            'email_config_min_hours_per_day' => [
                'label' => 'Minimum Number of Hours Required Per Day',
                'slug' => 'email_config_min_hours_per_day',
                'value' => null,
            ],
            'email_config_daily_mail_excluded_departments' => [
                'label' => 'Daily Time Tracker Mail Excluded Departments',
                'slug' => 'email_config_daily_mail_excluded_departments',
                'value' => null,
            ],
            'email_config_task_overdue_email_recipients' => [
                'label' => 'Task Overdue Email Recipients',
                'slug' => 'email_config_task_overdue_email_recipients',
                'value' => null,
            ],
            'show_daily_status_report_page' => [
                'label' => 'Enable Daily Status Report',
                'slug' => 'show_daily_status_report_page',
                'value' => 1,
            ]
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
        'list' => [
            'Base Currency' => 'USD',
            'gstin' => 'ABCD123654789',
            'Cin' => 'ABCDEDF12365467890',
            'Tax Rate' => '10',
            'Account Details' => '<p>Bank Name: TEST</p><p>Bank
            current acc# : 12301234567890
            </p><p>Name : TEST COMPANY Pvt Ltd
            </p><p>Branch : TEST branch
            </p><p>Swift code : TEST78965
            </p><p>IFSE code : TEST14893<br></p>',
        ]
    ],
    'client' => [
        'currency' => 'USD'
    ],
    'users' => [
        'designation' => [
            'Web Developer',
            'HR',
            'CTO',
            'Lead Front End Developer',
            'Lead Web Developer',
            'Quality Analyst',
            'Mobile Developer',
            'Back End Developer',
            'CEO',
            'HR Manager',
            'Project Coordinator',
            'Intern',
            'Account Manager',
            'HR Intern',
            'VP Business Development',
            'Client',
            'Director',
            'Business Development Executive',
            'Associate Front End Developer',
            'Junior Front End Developer',
            'Freelance PHP Developer',
            'Junior PHP Developer',
            'Server Support',
            'Senior Web Developer',
            'Junior Web Developer'
        ],
    ],
];
