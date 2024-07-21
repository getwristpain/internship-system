<?php

namespace Database\Seeders;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                'email' => 'admin@test.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Administrator',
                    'slug' => 'admin',
                ],
            ],
            [
                'name' => 'Student Test',
                'email' => 'student@test.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Siswa',
                    'slug' => 'student',
                ],
            ],
            [
                'name' => 'Teacher Test',
                'email' => 'teacher@test.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Guru',
                    'slug' => 'teacher',
                ],
            ],
        ];

        foreach ($users as $userData) {
            // Create or get the role
            $role = Role::updateOrCreate(
                ['slug' => $userData['role']['slug']],
                ['name' => $userData['role']['name']]
            );

            // Create or update the user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            // Attach the role to the user through the pivot table
            $user->roles()->attach($role->id);
        }
    }
}
