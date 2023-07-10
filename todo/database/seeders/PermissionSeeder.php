<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['title' => 'View all']);
        Permission::create(['title' => 'View']);
        Permission::create(['title' => 'Create']);
        Permission::create(['title' => 'Update']);
        Permission::create(['title' => 'Delete']);
    }
}
