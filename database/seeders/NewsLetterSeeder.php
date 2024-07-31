<?php

namespace Database\Seeders;

use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Newsletter::factory()->count(1)->create(['publish_date' => Carbon::now()->format('Y-m-01')]);
    }
}
