<?php

namespace Database\Factories;

use App\Models\ChecklistReport;
use App\Models\TaxonomyList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistReportFactory extends Factory
{
    protected $model = ChecklistReport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userId = User::active()->whereIn('role_id', [1, 3, 2, 6])->inRandomOrder()->first()->id;
        $title = TaxonomyList::where(['taxonomy_id' => 1, 'user_id' => $userId])->inRandomOrder()->first()->title ?? $this->faker->unique()->word;

        return [
            'user_id' => $userId,
            'title' => $title,
            'note' => $this->faker->paragraph(1),
            'checklists' => serialize(['title' => $title]),
            'added_on' => Carbon::now()->subDays(rand(0, 7))
        ];
    }
}
