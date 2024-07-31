<?php

namespace Database\Seeders;

use App\Models\IssueCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IssueCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        IssueCategory::truncate();
        Schema::enableForeignKeyConstraints();

        $types = config('seeder-config.issue-records.types');
        foreach ($types as $type) {
            IssueCategory::create([
                'title' => $type['title'],
                'slug' => $type['slug'],
                'status' => 1,
            ]);
        }
    }
}
