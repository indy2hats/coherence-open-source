<?php

namespace Database\Seeders;

use App\Models\Compensatory;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LeavesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Leave::truncate();
        Schema::enableForeignKeyConstraints();

        Leave::factory()->count(1)->create([
            'from_date' => Carbon::now()->format('Y-m-d'),
            'to_date' => Carbon::now()->format('Y-m-d'),
            'status' => 'Approved',
            'approved_by' => User::where('role_id', 5)->inRandomOrder()->first()->id,
        ]);
        Leave::factory()->count(4)->create();
        Compensatory::factory()->count(2)->create();
    }
}
