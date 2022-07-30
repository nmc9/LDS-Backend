<?php

namespace Database\Factories;

use App\Models\Bringable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BringableItem>
 */
class BringableItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bringable_id' => Bringable::factory(),
            'assigned_id' => User::factory(),
            'required' => $this->faker->randomNumber(),
            'acquired' => $this->faker->randomNumber(),
        ];
    }
}
