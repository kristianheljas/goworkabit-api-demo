<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkBit;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkBitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkBit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->company(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => now()->addDays(mt_rand(1, 7))->format('Y-m-d'),
        ];
    }
}
