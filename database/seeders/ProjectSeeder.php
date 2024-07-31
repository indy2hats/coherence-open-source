<?php

namespace Database\Seeders;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory()->count(5)->create(['client_id' => 1]);
        Project::factory()->count(5)->create(['client_id' => 2]);
        self::projectOverdue();
        self::projectOverdue();
    }

    public static function projectOverdue()
    {
        $date = Carbon::now()->subDays(rand(10, 20));
        $date = Carbon::parse($date)->addDays(rand(2, 6));
        Project::factory()->count(1)->create([
            'is_archived' => 1,
            'start_date' => $date,
            'end_date' => $date,
        ]);
    }
}
