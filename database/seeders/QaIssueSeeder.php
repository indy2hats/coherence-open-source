<?php

namespace Database\Seeders;

use App\Models\QaIssue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class QaIssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        QaIssue::truncate();
        Schema::enableForeignKeyConstraints();

        QaIssue::factory()->count(5)->create();
    }
}
