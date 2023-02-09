<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
    public function startElection()
    {
        return $this->state(function (array $attributes) {
            return [
                'start' => now(),
            ];
        });
    }
    public function stopElection()
    {
        return $this->state(function (array $attributes) {
            return [
                'start' => now(),
                'end' => now(),
            ];
        });
    }
}
