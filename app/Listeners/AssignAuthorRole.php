<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class AssignAuthorRole
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;

        // Define roles that should automatically grant Author role
        $rolesThatGrantAuthor = ['Owner', 'Admin', 'Staff'];

        if ($user->hasAnyRole($rolesThatGrantAuthor)) {
            // Check if the Author role exists
            $authorRole = Role::firstOrCreate(['name' => 'Author']);

            if ($authorRole && !$user->hasRole($authorRole)) {
                $user->assignRole($authorRole);
            }
        } else {
            // Optional: Remove the Author role if the user no longer has the required roles
            $authorRole = Role::where('name', 'Author')->first();

            if ($authorRole && $user->hasRole($authorRole)) {
                $user->removeRole($authorRole);
            }
        }
    }
}
