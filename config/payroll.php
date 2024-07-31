<?php

return [
    'salary_component' => [
        'type' => ['earning', 'deduction'],
        'status' => ['Inactive', 'Active'],
        'success_message' => [
            'create' => 'Component Created',
            'update' => 'Component Updated',
            'delete' => 'Component Removed'
        ],
        'error' => [
            'message' => 'Something went wrong',
        ]
    ],
    'payrolls' => [
        'status' => ['processing', 'complete'],
        'default_csv_headings' => [
            0 => 'Employee ID',
            1 => 'Employee Name',
            5 => 'Gross Salary',
            6 => 'Incentives',
            7 => 'Total Earnings',
            20 => 'Loss of Pay',
            21 => 'No of Leaves',
            22 => 'Net Salary',
            23 => 'Monthly CTC'
        ],
        'success_message' => [
            'import' => 'Import Successfull',
            'export' => 'Export Successfull',
            'status_update' => 'Status Updated',
        ],

        'error' => [
            'message' => 'Something went wrong',
            'export' => 'Export Failed',
            'processed_update_error' => 'Payroll Exist and Processed',
            'csv_components_mismatch' => 'Component Mistmatch'
        ],

    ],
    'payroll_user' => [
        'status' => ['pending', 'approved'],
        'update_success' => [
            'message' => 'Payroll Updated'
        ],
        'error' => [
            'message' => 'Something went wrong',
            'calculation_mistmatch' => 'Calculation Mistmatch in Form',
            'export_error_message' => 'Payslip Generation Failed',
            'export_processing_error_message' => 'Payroll is on Processing'
        ],
    ],
    'error' => ['message' => 'Something went wrong'],

];
