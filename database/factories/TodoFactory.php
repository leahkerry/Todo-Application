<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    protected $model = \App\Models\Todo::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'completed' => $this->faker->boolean,
            'due_date' => $this->faker->date,
        ];
    }
}
