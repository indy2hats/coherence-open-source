<?php

namespace Database\Seeders;

use App\Models\Guideline;
use App\Models\TaxonomyList;
use Illuminate\Database\Seeder;

class GuidelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::addTags();
        Guideline::factory()->count(4)->create();
    }

    public static function addTags()
    {
        TaxonomyList::factory()->count(4)->create([
            'taxonomy_id' => 7,
            'user_id' => null,
            'parent_id' => null
        ]);
    }
}
