<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'         =>  $this->faker->text(100),
            'description'   =>  $this->faker->paragraph(5),
            'keywords'      =>  explode(' ', $this->faker->text(200)),
            'content'       =>  $this->faker->paragraph(15),
            "paragraph"     =>  [
                $this->faker->paragraph(10),
                $this->faker->paragraph(10),
                $this->faker->paragraph(10),
            ]
        ];
    }
}
