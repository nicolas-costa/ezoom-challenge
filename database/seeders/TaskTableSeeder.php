<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::limit(5)->get(['id'])->each(function (User $user) {
            Task::factory()->count(5)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
