<?php

namespace Database\Factories;

use App\Models\Guideline;
use App\Models\TaxonomyList;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuidelineFactory extends Factory
{
    protected $model = Guideline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tag[] = TaxonomyList::where(['taxonomy_id' => 7])->inRandomOrder()->first()->title ??
            TaxonomyList::factory()->count(4)->create([
                'taxonomy_id' => 7,
                'user_id' => null,
                'parent_id' => null
            ])[0]['title'];

        return [
            'type' => serialize($tag),
            'title' => $this->faker->word,
            'content' => $this->faker->paragraph(1)
        ];
    }
}
