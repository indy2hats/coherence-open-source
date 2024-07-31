<?php

namespace Database\Seeders;

use App\Models\UserWish;
use Illuminate\Database\Seeder;

class UserWishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserWish::factory()->count(1)->create([
            'title' => date('d M Y'),
            'type' => 'text',
            'image' => '<p>Have a Nice Day</p>'
        ]);
    }
}
