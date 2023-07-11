<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['update', 'delete'];

        $user1 = User::factory()->create(['email' => 'user@user.com']);
        $user1->givePermissionsTo('update');
        $user1->givePermissionsTo('delete');

        $user2 = User::factory()->create(['email' => 'test@test.com']);
        $user2->givePermissionsTo('update');

        User::factory(10)->create()->each(function ($user) use ($permissions) {
            $user->givePermissionsTo(fake()->randomElement($permissions));
        });
    }
}
