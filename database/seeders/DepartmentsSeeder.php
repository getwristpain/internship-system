<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Department;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
                            'email' => 'tkgsp@mail.test',
                            'password' => 'password',
                            'role' => 'Staff',
                            'status' => 'Active',
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
                            'email' => 'tsija@mail.test',
                            'password' => 'password',
                            'role' => 'Staff',
                            'status' => 'Active',
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
                    // Fetch the user status by name
                    $status = UserStatus::where('name', $userData['status'])->first();

                    if (!$status) {
                        // Handle missing status (optional)
                        continue;
                    }

                    // Create or get the role
                    $role = Role::firstOrCreate(['name' => $userData['role']]);

                    // Create or update the user
                    $user = User::updateOrCreate(
                        ['email' => $userData['email']],
                        [
                            'name' => $userData['name'],
                            'password' => Hash::make($userData['password']),
                            'status_id' => $status->id, // Set the status_id
                        ]
                    );

                    // Assign the role to the user
                    $user->assignRole($role);

                    // Attach the user to the department
                    $user->departments()->syncWithoutDetaching($department->id);
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
