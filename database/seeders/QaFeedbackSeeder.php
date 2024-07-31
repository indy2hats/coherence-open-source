<?php

namespace Database\Seeders;

use App\Models\TaskRejection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class QaFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TaskRejection::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call(QaIssueSeeder::class);

        TaskRejection::factory()->count(5)->create();
    }
}
