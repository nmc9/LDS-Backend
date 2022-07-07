<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friend>
 */
class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'from_user_id' => User::factory(),
            'to_user_id' => User::factory(),
            'accepted' => false,
            'token' => \Str::random(10),
        ];
    }

    public function accepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'accepted' => true,
            ];
        });
    }
}
