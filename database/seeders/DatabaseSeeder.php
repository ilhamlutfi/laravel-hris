<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Responsibility;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
        Company::factory(100)->create();
        Team::factory(100)->create();
        Role::factory(100)->create();
        Responsibility::factory(100)->create();
        Employee::factory(100)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
