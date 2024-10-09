<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleService
{
    /**
     * Get roles that are included in the specified array.
     *
     * @param array $roles
     * @return array
     */
    public static function getRoles(array $roles = []): array
    {
        // Check if the user is not an admin and remove 'admin' from the roles if present
        if (!self::userIsAdmin()) {
            $roles = array_filter($roles, fn($role) => $role !== 'admin');
        }

        return self::formatRoles(Role::whereIn('name', $roles)->get());
    }

    /**
     * Get roles excluding specified roles along with 'owner' and 'admin' if applicable.
     *
     * @param array $excludeRoles
     * @return array
     */
    public static function getRolesExcluding(array $excludeRoles): array
    {
        $rolesToExclude = self::getDefaultExclusions($excludeRoles);
        return self::fetchRolesExcluding($rolesToExclude);
    }

    /**
     * Fetch roles excluding specified roles from the database.
     *
     * @param array $excludeRoles
     * @return array
     */
    private static function fetchRolesExcluding(array $excludeRoles): array
    {
        return self::formatRoles(Role::whereNotIn('name', $excludeRoles)->get());
    }

    /**
     * Determine if the authenticated user is an admin.
     *
     * @return bool
     */
    private static function userIsAdmin(): bool
    {
        $user = User::find(Auth::id());
        return $user ? $user->hasRole('admin') : false;
    }

    /**
     * Get the default roles to exclude based on user role.
     *
     * @param array $excludeRoles
     * @return array
     */
    private static function getDefaultExclusions(array $excludeRoles): array
    {
        $defaultExclusions = ['owner'];
        if (Auth::check() && !self::userIsAdmin()) {
            $defaultExclusions[] = 'admin';
        }
        return array_merge($excludeRoles, $defaultExclusions);
    }

    /**
     * Format the roles into an array with value and text attributes.
     *
     * @param $roles
     * @return array
     */
    private static function formatRoles($roles): array
    {
        return $roles->map(fn($role) => [
            'value' => $role->name,
            'text' => Str::title($role->name),
        ])->toArray();
    }
}
