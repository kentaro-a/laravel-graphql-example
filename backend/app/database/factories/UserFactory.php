<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
			'name' => $this->faker->name(),
			'mail' => $this->faker->unique()->safeEmail(),
			'password' => 'aaaa',
			'created_at' => $this->faker->dateTimeInInterval('-2 month', '-1 month'),
			'last_login_at' => $this->faker->dateTimeInInterval('-1 month'),
        ];
    }
}
