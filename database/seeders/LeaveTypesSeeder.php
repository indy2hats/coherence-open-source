<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $leaveTypes = [
            ['name' => 'Casual', 'slug' => 'Casual', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Medical', 'slug' => 'Medical', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Compensatory off', 'slug' => 'Compensatory', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LOP', 'slug' => 'LOP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Paternity', 'slug' => 'Paternity', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Maternity', 'slug' => 'Maternity', 'created_at' => $now, 'updated_at' => $now]
        ];

        LeaveType::insert($leaveTypes);
    }
}
