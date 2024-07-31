<?php

namespace Database\Seeders;

use App\Models\FixedOverhead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FixedOverheadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        FixedOverhead::truncate();
        Schema::enableForeignKeyConstraints();

        FixedOverhead::create([
            'type' => 'Rent',
            'amount' => 40000,
            'description' => 'Rent for office',
        ]);
    }
}
