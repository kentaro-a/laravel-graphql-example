<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
			'usr_id' => User::whereNotIn("id", [1, 2])->inRandomOrder()->first()->id,
			'name' => $this->faker->sentence(),
			'status' => $this->faker->biasedNumberBetween(0,9),
        ];
    }

}
