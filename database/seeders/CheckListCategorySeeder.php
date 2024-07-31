<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\ChecklistCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CheckListCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Checklist::truncate();
        ChecklistCategory::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            [
                'title' => 'PHP Code',
                'slug' => 'php-code',
                'status' => 1
            ],
            [
                'title' => 'JS Code',
                'slug' => 'js-code',
                'status' => 1
            ],
            [
                'title' => 'HTML Code',
                'slug' => 'html-code',
                'status' => 1
            ],
            [
                'title' => 'MySQL updates',
                'slug' => 'mysql-updates',
                'status' => 1
            ],
            [
                'title' => 'Going Live',
                'slug' => 'going-live',
                'status' => 1
            ]
        ];

        foreach ($categories as $category) {
            ChecklistCategory::create([
                'title' => $category['title'],
                'slug' => $category['slug'],
                'status' => $category['status'],
            ]);
        }
    }
}
