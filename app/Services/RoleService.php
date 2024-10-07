<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleService
{
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
     * Get roles including specified roles and ensure 'admin' is omitted if user is not admin.
     *
     * @param array $includingRoles
     * @return array
     */
    public static function getRolesIncluding(array $includingRoles): array
    {
        $includingRoles = self::adjustIncludingRoles($includingRoles);
        $roles = self::fetchRolesIncluding($includingRoles);
        return self::formatRoles($roles->whereNotIn('name', ['owner']));
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
     * Fetch roles including specified roles from the database.
     *
     * @param array $includingRoles
     * @return \Illuminate\Support\Collection
     */
    private static function fetchRolesIncluding(array $includingRoles)
    {
        return Role::whereIn('name', $includingRoles)->get();
    }

    /**
     * Adjust the including roles by removing 'admin' if the user is not an admin.
     *
     * @param array $includingRoles
     * @return array
     */
    private static function adjustIncludingRoles(array $includingRoles): array
    {
        if (Auth::check() && !self::userIsAdmin()) {
            return array_filter($includingRoles, fn($role) => $role !== 'admin');
        }
        return $includingRoles;
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
