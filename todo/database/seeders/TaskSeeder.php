<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $date = date('Y-m-d');
        $filePath =  storage_path("app/public/uploads/$date/");

        if (!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        Task::factory(50)
            ->create()
            ->each(function ($task) use ($filePath, $faker, $date) {
                $task->tags()->sync(Tag::factory(rand(1, 5))->create());

                $fakerFileName = $faker->image(
                    $filePath,
                    500,
                    500
                );

                $mediaPath = storage_path("app/public/uploads/$date/".basename($fakerFileName));
                $task->addMedia($mediaPath)
                    ->toMediaCollection('task-image');
            });


    }
}
