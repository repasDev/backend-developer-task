<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class FolderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
               'title' => $this->faker->sentence
        ];
    }

    public function klemenFolder(): FolderFactory
    {
        return $this->state(function (array $attribtues){
            return [
                'user_id' => 1
            ];
        });
    }

    public function alenFolder(): FolderFactory
    {
        return $this->state(function (array $attribtues){
            return [
                'user_id' => 2
            ];
        });
    }
}
