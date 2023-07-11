<?php

namespace Database\Seeders;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        TaskList::factory(20)
            ->create()
            ->each(function ($list) use ($users) {
                $list->users()->sync($users->random(4));
            });
    }
}
