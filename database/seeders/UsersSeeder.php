<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
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
                'name' => 'Student Test',
                'email' => 'student@mail.test',
                'password' => 'password',
                'role' => 'Student',
                'status' => 'pending',
            ],
            [
                'name' => 'Teacher Test',
                'email' => 'teacher@mail.test',
                'password' => 'password',
                'role' => 'Teacher',
                'status' => 'pending',
            ],
        ];

        foreach ($users as $userData) {
            // Create or get the role
            $role = Role::updateOrCreate(
                ['name' => $userData['role']]
            );

            $status = UserStatus::where(['name' => $userData['status']])->first();

            // Create or update the user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'status_id' => $status->id,
                ]
            );

            // Assign the role to the user
            $user->assignRole($role);
        }
    }
}
