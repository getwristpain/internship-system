<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Owner',
                'slug' => 'owner',
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
            ],
            [
                'name' => 'Staf Jurusan',
                'slug' => 'department-staff',
            ],
            [
                'name' => 'Siswa',
                'slug' => 'student',
            ],
            [
                'name' => 'Guru',
                'slug' => 'teacher',
            ],
            [
                'name' => 'Supervisi',
                'slug' => 'supervisor',
            ],
        ];

        collect($roles)->map(function ($role) {
            return Role::firstOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        });
    }
}
