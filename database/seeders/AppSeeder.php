<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory(1)->admin()->create();
        User::factory(5)->create()->each(function ($user) {
            Todo::factory(rand(0, 3))->create([
                'user_id' => $user->id
            ]);
            Todo::factory(rand(1, 2))->softdeleted()->create([
                'user_id' => $user->id
            ]);
            Todo::factory(rand(1, 2))->completed()->create([
                'user_id' => $user->id
            ]);
        });
    }
}
