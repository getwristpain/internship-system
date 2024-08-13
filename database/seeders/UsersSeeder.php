<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'test@admin.test',
                'password' => 'password',
                'role' => 'Admin',
            ],
            [
                'name' => 'Student Test',
                'email' => 'test@student.test',
                'password' => 'password',
                'role' => 'Student',
            ],
            [
                'name' => 'Teacher Test',
                'email' => 'test@teacher.test',
                'password' => 'password',
                'role' => 'Teacher',
            ],
        ];

        foreach ($users as $userData) {
            // Create or get the role
            $role = Role::updateOrCreate(
                ['name' => $userData['role']]
            );

            // Create or update the user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            // Assign the role to the user
            $user->assignRoles([$role]);
        }
    }
}
