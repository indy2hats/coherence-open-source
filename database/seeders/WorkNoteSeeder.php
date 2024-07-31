<?php

namespace Database\Seeders;

use App\Models\WorkNote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class WorkNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        WorkNote::truncate();
        Schema::enableForeignKeyConstraints();

        WorkNote::factory()->count(25)->create();
    }
}
