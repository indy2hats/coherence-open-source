<?php

namespace Database\Seeders;

use App\Models\DailyStatusReport;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyStatusReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($userId)
    {
        for ($i = 1; $i < 7; $i++) {
            $date = self::getWeekdays($i);
            if ($date !== false) {
                $response = DailyStatusReport::factory()->count(1)->create([
                    'user_id' => $userId,
                    'added_on' => $date->format('Y-m-d'),
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }

        return $response;
    }

    public static function getWeekdays($days)
    {
        $taskDate = Carbon::now()->subDays($days);
        if ($taskDate->dayOfWeek === 0 || $taskDate->dayOfWeek === 6) {
            return false;
        }

        return $taskDate;
    }
}
