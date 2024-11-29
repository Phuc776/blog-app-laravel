<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role; // Import model Role

class UserSeeder extends Seeder
{
    public function run()
    {
        // Lấy id của vai trò 'User' hoặc 'Admin'
        $roleId = Role::where('name', 'User')->first()->id;

        // Thêm user với id = 1 và role_id
        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password1'),
            'role_id' => $roleId, // Chỉ định role_id
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Thêm user với id = 2 và role_id
        $roleId = Role::where('name', 'User')->first()->id;
        User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password2'),
            'role_id' => $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
