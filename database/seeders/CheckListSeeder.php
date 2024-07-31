<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\ChecklistCategory;
use App\Models\ChecklistReport;
use App\Models\TaxonomyList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CheckListSeeder extends Seeder
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
        Schema::enableForeignKeyConstraints();

        $phpChecklistsTitle = [
            'All functions have comments',
            'All comments are strictly PHPDoc format',
        ];

        $htmlChecklistsTitle = [
            'All code indentations are validated',
            'No repeating IDs in a page',
        ];

        $goLiveChecklistsTitle = [
            'Remove the maintenance message',
            'Make sure error reporting is turned off',
        ];

        foreach ($phpChecklistsTitle as $phpChecklist) {
            Checklist::create([
                'title' => $phpChecklist,
                'category_id' => ChecklistCategory::where('slug', 'php-code')->first()->id,
                'tools' => '',
                'status' => 1,
            ]);
        }

        foreach ($htmlChecklistsTitle as $htmlChecklist) {
            Checklist::create([
                'title' => $htmlChecklist,
                'category_id' => ChecklistCategory::where('slug', 'html-code')->first()->id,
                'tools' => '',
                'status' => 1,
            ]);
        }

        foreach ($goLiveChecklistsTitle as $goLiveChecklist) {
            Checklist::create([
                'title' => $goLiveChecklist,
                'category_id' => ChecklistCategory::where('slug', 'going-live')->first()->id,
                'tools' => '',
                'status' => 1,
            ]);
        }
    }

    public static function addChecklist($userId)
    {
        $checklist = TaxonomyList::where(['slug' => 'checklist'])->first();

        $recentChecklist = TaxonomyList::factory()->count(1)->create([
            'user_id' => $userId,
            'taxonomy_id' => 1,
            'parent_id' => null,
            'title' => 'Shared-'.$checklist->title,
            'slug' => 'Shared-'.$checklist->slug
        ]);

        $checklistChild = TaxonomyList::where(['parent_id' => $checklist->id])->first();
        TaxonomyList::factory()->count(1)->create([
            'user_id' => User::whereIn('role_id', [1, 2, 3, 5])->inRandomOrder()->first()->id,
            'taxonomy_id' => 1,
            'parent_id' => $recentChecklist[0]['id'],
            'title' => $checklistChild->title,
            'slug' => $checklistChild->slug
        ]);
    }

    public static function checklistReport($userId)
    {
        ChecklistReport::factory()->count(1)->create(['user_id' => $userId, 'added_on' => Carbon::now()]);
    }
}
