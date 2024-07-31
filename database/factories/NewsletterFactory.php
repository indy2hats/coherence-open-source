<?php

namespace Database\Factories;

use App\Models\Newsletter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class NewsLetterFactory extends Factory
{
    protected $model = Newsletter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'publish_date' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null)->format('Y-m-01'),
            'screen_shot' => 'newsletters/screenshots/test.jpg',
            'newsletter' => 'newsletters/documents/Sample.pdf',
            // 'screen_shot' =>  UploadedFile::fake()->image('test.jpg')->store('newsletters/screenshots/'),
            // 'newsletter' => UploadedFile::fake()->create('document.pdf', 9885)->store('newsletters/documents/'),
            'status' => 1
        ];
    }
}
