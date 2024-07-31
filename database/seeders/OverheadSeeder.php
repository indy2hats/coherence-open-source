<?php

namespace Database\Seeders;

use App\Models\Overhead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class OverheadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Overhead::truncate();
        Schema::enableForeignKeyConstraints();

        Overhead::factory()->count(10)->create();
    }
}
