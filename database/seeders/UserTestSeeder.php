<?php

namespace Database\Seeders;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'test@admin.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Administrator',
                    'slug' => 'admin',
                ],
            ],
            [
                'name' => 'Department Staff',
                'email' => 'test@department.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Staf Jurusan',
                    'slug' => 'department-staff',
                ],
            ],
            [
                'name' => 'Student Test',
                'email' => 'test@student.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Siswa',
                    'slug' => 'student',
                ],
            ],
            [
                'name' => 'Teacher Test',
                'email' => 'test@teacher.com',
                'password' => 'password',
                'role' => [
                    'name' => 'Guru',
                    'slug' => 'teacher',
                ],
            ],
        ];

        collect($users)->map(function ($userData) {
            $role = Role::firstOrCreate(
                ['slug' => $userData['role']['slug']],
                ['name' => $userData['role']['name']]
            );

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role_id' => $role->id,
                ]
            );

            $user->role()->associate($role);
            $user->save();
        });
    }
}
