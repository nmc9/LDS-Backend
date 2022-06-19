<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->realText,
            'location' => $this->faker->streetAddress,
            'start_datetime' => $this->faker->date('Y-m-d H:i:s'),
            "end_datetime" => $this->faker->date('Y-m-d H:i:s'),
            'owner_id' => User::factory()
        ];
    }
}
