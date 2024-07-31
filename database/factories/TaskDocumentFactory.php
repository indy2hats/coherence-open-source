<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskDocumentFactory extends Factory
{
    protected $model = TaskDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'path' => \Illuminate\Http\UploadedFile::fake()->create('test.pdf')->store('tasks/documents'),
            'path' => 'tasks/documents/Sample.pdf',
            'task_id' => Task::factory(),
        ];
    }
}
