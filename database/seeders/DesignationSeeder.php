<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Designation::truncate();
        Schema::enableForeignKeyConstraints();

        $designations = config('seeder-faker.users.designation');

        foreach ($designations as $designation) {
            Designation::create([
                'name' => $designation
            ]);
        }
    }
}
