<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Todo;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->dateTime(),
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s')
            ];
        });
    }

    public function softdeleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => date('Y-m-d H:i:s')
            ];
        });
    }
}
