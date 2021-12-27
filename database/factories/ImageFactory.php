<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url'       =>  'https://picsum.photos/' . \Arr::random([400, 450, 500]) . '/' . \Arr::random([400, 450, 500]),
            'altText'   =>  $this->faker->text(200)
        ];
    }
}
