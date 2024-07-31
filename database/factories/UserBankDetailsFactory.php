<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserBankDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserBankDetailsFactory extends Factory
{
    protected $model = UserBankDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'bank_name' => \Faker\Provider\de_DE\Payment::bank(),
            'branch' => $this->faker->unique()->city,
            'account_no' => $this->faker->unique()->bankAccountNumber,
            'ifsc' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'pan' => \Faker\Provider\at_AT\Payment::vat(false),
            'uan' => \Faker\Provider\kk_KZ\Person::individualIdentificationNumber(),
        ];
    }
}
