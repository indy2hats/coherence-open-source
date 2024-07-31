<?php

namespace Database\Seeders;

use App\Models\OverheadType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class OverheadTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        OverheadType::truncate();
        Schema::enableForeignKeyConstraints();

        $overheadTypes = ['Rent', 'Power charges', 'A/c', 'Operational', 'Total Expense'];

        foreach ($overheadTypes as $type) {
            OverheadType::create([
                'name' => $type,
            ]);
        }
    }
}
