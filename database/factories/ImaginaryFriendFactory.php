<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImaginaryFriend>
 */
class ImaginaryFriendFactory extends Factory
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
            'to_user_email' => $this->faker->email,
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
