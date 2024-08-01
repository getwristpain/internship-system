<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Owner',
            ],
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Staff',
            ],
            [
                'name' => 'Student',
            ],
            [
                'name' => 'Teacher',
            ],
            [
                'name' => 'Supervisor',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']]
            );
        }
    }
}
