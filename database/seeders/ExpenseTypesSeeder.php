<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseType::truncate();
        $types = config('seeder-config.expense-type');

        foreach ($types as $type) {
            ExpenseType::create(['name' => $type]);
        }
    }
}
