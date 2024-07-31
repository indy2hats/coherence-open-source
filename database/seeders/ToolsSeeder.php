<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ToolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WorkNoteSeeder::class);
        $this->call(IssueRecordSeeder::class);
        $this->call(UserCredentialSeeder::class);
        $this->call(QaFeedbackSeeder::class);
        $this->call(GuidelineSeeder::class);
    }
}
