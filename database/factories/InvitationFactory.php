<?php

namespace Database\Factories;

use App\Library\Constants;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'inviter_id' => $this->faker->boolean ? User::factory() : null,
            'status' => $this->faker->randomElement([
                Constants::INVITATION_PENDING,
                Constants::INVITATION_ACCEPTED,
                Constants::INVITATION_DECLINED,
            ]),
            'token' => \Str::random(60),
            'notes' => $this->faker->realText,
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Constants::INVITATION_PENDING,
            ];
        });
    }


    public function accepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Constants::INVITATION_ACCEPTED,
            ];
        });
    }


    public function declined()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Constants::INVITATION_DECLINED,
            ];
        });
    }
}
