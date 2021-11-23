<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\Note;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->userKlemen()
            ->has(Folder::factory()->count(10)
                ->has(Note::factory()->count(10)->state(function (array $attributes, Folder $folder) {
                return [
                    'folder_id' => $folder->id,
                    'user_id' => 1
                ];
                })
                )
            )
            ->create();

        User::factory()->userAlen()
            ->has(Folder::factory()->count(10)
                ->has(Note::factory()->count(10)->state(function (array $attributes, Folder $folder) {
                    return [
                        'folder_id' => $folder->id,
                        'user_id' => 2
                    ];
                })
                )
            )
            ->create();
    }
}
