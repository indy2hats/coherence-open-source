<?php

namespace Database\Seeders;

use App\Models\UserCredential;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        UserCredential::truncate();
        Schema::enableForeignKeyConstraints();

        UserCredential::factory()->count(25)->create();
    }
}
