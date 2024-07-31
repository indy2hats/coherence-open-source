<?php

namespace Database\Seeders;

use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SalaryComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        SalaryComponent::truncate();
        Schema::enableForeignKeyConstraints();

        $salaryComponent = [
            [
                'title' => 'Basic Pay',
                'type' => 'earning',
            ],
            [
                'title' => 'HRA',
                'type' => 'earning',
            ],
            [
                'title' => 'Special Allowance',
                'type' => 'earning',
            ],
            [
                'title' => 'EPF',
                'type' => 'deduction',
            ],
            [
                'title' => 'ESI',
                'type' => 'deduction',
            ],
            [
                'title' => 'Professional Tax',
                'type' => 'deduction',
            ],
            [
                'title' => 'TDS',
                'type' => 'deduction',
            ],
            [
                'title' => 'Labour Welfare Fund',
                'type' => 'deduction',
            ],
            [
                'title' => 'VPF',
                'type' => 'deduction',
            ],
            [
                'title' => 'Insurance',
                'type' => 'deduction',
            ],
            [
                'title' => 'Advance Salary',
                'type' => 'deduction',
            ]

        ];
        foreach ($salaryComponent as  $component) {
            SalaryComponent::create([
                'title' => $component['title'],
                'type' => $component['type'],
                'status' => 1,
            ]);
        }
    }
}
