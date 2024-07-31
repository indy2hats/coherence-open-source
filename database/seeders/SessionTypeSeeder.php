<?php

namespace Database\Seeders;

use App\Models\SessionType;
use Illuminate\Database\Seeder;

class SessionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SessionType::truncate();

        $types = [
            [
                'title' => 'Development',
                'slug' => 'development',
                'status' => 1
            ],
            [
                'title' => 'Project Management',
                'slug' => 'project-management',
                'status' => 1
            ],
            [
                'title' => 'QA',
                'slug' => 'qa',
                'status' => 1
            ],
            [
                'title' => 'Technical Support',
                'slug' => 'technical-support',
                'status' => 1
            ],
            [
                'title' => 'Others',
                'slug' => 'others',
                'status' => 1
            ]
        ];

        foreach ($types as $type) {
            SessionType::create([
                'title' => $type['title'],
                'slug' => $type['slug'],
                'status' => $type['status'],
            ]);
        }
    }
}
