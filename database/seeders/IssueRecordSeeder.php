<?php

namespace Database\Seeders;

use App\Models\IssueRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IssueRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        IssueRecord::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call(IssueCategorySeeder::class);

        IssueRecord::factory()->count(4)->create();
    }
}
