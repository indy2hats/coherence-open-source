<?php

namespace Database\Factories;

use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxonomyListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return
            [
                'user_id' => null,
                'taxonomy_id' => Taxonomy::inRandomOrder()->first()->id,
                'title' => ucwords($this->faker->unique()->word),
                'slug' => $this->faker->unique()->word,
                'parent_id' => null

            ];
    }
}
