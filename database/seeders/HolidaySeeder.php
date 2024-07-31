<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Holiday::truncate();
        Schema::enableForeignKeyConstraints();

        Holiday::factory()->count(2)->create();
    }
}
