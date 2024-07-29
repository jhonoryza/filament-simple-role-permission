<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->where('name', User::SUPER)->first();
        User::query()->firstOrCreate([
            'email' => 'test@example.com',
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);
    }
}
