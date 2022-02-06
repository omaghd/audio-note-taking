<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'path'  => '/storage/audios/test.mp3',
        ];
    }
}
