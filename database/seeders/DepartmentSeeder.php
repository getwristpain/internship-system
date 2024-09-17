<?php

namespace Database\Seeders;

use Exception;
use App\Models\User;
use App\Models\Group;
use App\Models\Department;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class DepartmentSeeder extends Seeder
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
                            'email' => 'tkgsp@example.com',
                            'password' => 'password',
                            'role' => 'staff',
                            'status' => 'active',
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
                            'email' => 'tsija@example.com',
                            'password' => 'password',
                            'role' => 'staff',
                            'status' => 'active',
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

        // Start a database transaction
        DB::beginTransaction();

        try {
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
                            throw new Exception("User status '{$userData['status']}' not found.");
                        }

                        // Create or get the role
                        $role = Role::firstOrCreate(['name' => $userData['role']]);

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

            // Commit the transaction if everything is successful
            DB::commit();
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Log the error message
            Log::error('Error seeding departments: ' . $e->getMessage());

            // Optionally, rethrow the exception if you want it to break the seeder
            throw $e;
        }
    }
}
