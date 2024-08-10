<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'departments' => [
                [
                    'name' => 'Teknik Konstruksi Gedung Sanitasi dan Perawatan',
                    'code' => 'TKGSP',
                    'users' => [
                        [
                            'name' => 'Admin TKGSP',
                            'email' => 'tkgsp@staff.test',
                            'password' => 'password',
                            'role' => 'Staff',
                        ],
                    ],
                    'groups' => [
                        [
                            'code' => 'TKGSP-XIII-KA',
                            'name' => 'Kelas A',
                            'level' => 'XIII',
                        ],
                    ],
                ],
                [
                    'name' => 'Teknik Sistem Informasi Jaringan dan Aplikasi',
                    'code' => 'TSIJA',
                    'users' => [
                        [
                            'name' => 'Admin SIJA',
                            'email' => 'tsija@staff.test',
                            'password' => 'password',
                            'role' => 'Staff',
                        ],
                    ],
                    'groups' => [
                        [
                            'code' => 'TSIJA-XIII-XSA',
                            'name' => 'Kelas A',
                            'level' => 'XIII',
                        ],
                    ],
                ],
                // Add more departments here
            ],
        ];

        foreach ($data['departments'] as $departmentData) {
            // Create or update the department
            $department = Department::updateOrCreate(
                ['code' => $departmentData['code']],
                ['name' => $departmentData['name']]
            );

            // Handle users
            if (isset($departmentData['users']) && is_array($departmentData['users'])) {
                foreach ($departmentData['users'] as $userData) {
                    // Create or get the role
                    $role = Role::firstOrCreate(['name' => $userData['role']]);

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

            // Handle groups
            if (isset($departmentData['groups']) && is_array($departmentData['groups'])) {
                foreach ($departmentData['groups'] as $groupData) {
                    Group::updateOrCreate(
                        ['code' => $groupData['code']],
                        [
                            'name' => $groupData['name'],
                            'level' => $groupData['level'],
                            'department_id' => $department->id,
                        ]
                    );
                }
            }
        }
    }
}
