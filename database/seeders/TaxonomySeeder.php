<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use App\Models\TaxonomyList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Taxonomy::truncate();
        TaxonomyList::truncate();
        Schema::enableForeignKeyConstraints();

        $taxonomies = config('seeder-faker.taxonomy.heads');
        $taxonomyList = config('seeder-faker.taxonomy.list');
        foreach ($taxonomies as $taxonomy) {
            $id = self::createTaxonomyHeads($taxonomy);

            if (in_array($taxonomy, array_keys($taxonomyList))) {
                TaxonomyList::factory()->count(1)->create([
                    'taxonomy_id' => $id,
                    'title' => $taxonomyList[$taxonomy],
                    'slug' => str_slug($taxonomy)
                ]);
            }

            if ($taxonomy == 'Checklist') {
                self::checkList($id, $taxonomy);
            }
        }
    }

    public static function createTaxonomyHeads($taxonomy)
    {
        return Taxonomy::create([
            'title' => $taxonomy,
            'slug' => str_slug($taxonomy),
        ])->id;
    }

    public static function checkList($id, $taxonomy)
    {
        TaxonomyList::factory()->count(1)->create([
            'taxonomy_id' => $id,
            'title' => $taxonomy,
            'slug' => str_slug($taxonomy),
            'parent_id' => null,
            'user_id' => 3
        ]);
        TaxonomyList::factory()->count(1)->create([
            'user_id' => 3,
            'taxonomy_id' => 1,
            'parent_id' => 1,
            'title' => 'Sample Checklist',
            'slug' => str_slug('Sample Checklist')
        ]);
    }
}
