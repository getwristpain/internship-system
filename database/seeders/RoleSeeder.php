<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'owner',
            'admin',
            'staff',
            'student',
            'teacher',
            'supervisor',
            'guest',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role]
            );
        }
    }
}
