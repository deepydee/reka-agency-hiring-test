<?php

namespace Database\Seeders;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class TaskListSeeder extends Seeder
{
    public function run(Faker $faker): void
    {
        $date = date('Y-m-d');
        $filePath =  storage_path("app/public/uploads/$date/");

        if (!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        $users = User::all();

        TaskList::factory(20)
            ->create()
            ->each(function ($list) use ($faker, $users, $filePath, $date) {
                $list->users()->sync($users->random(4));

                $fakerFileName = $faker->image(
                    $filePath,
                    500,
                    500
                );

                $mediaPath = storage_path("app/public/uploads/$date/".basename($fakerFileName));
                $list->addMedia($mediaPath)
                    ->toMediaCollection('list-image');
            });
    }
}
