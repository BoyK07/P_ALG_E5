<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;

class UserRoleService {
    /**
     * Assign roles to a user.
     *
     * @param User $user
     * @param array $roleNames
     * @return void
     */
    public function assignRoles(User $user, array $roleNames): void
    {
        // Filter to ensure only allowed roles are assigned
        $allowedRoles = ['buyer', 'maker'];
        if ($user->is_admin) {
            $allowedRoles[] = 'admin';
        }
        $validRoleNames = array_intersect($roleNames, $allowedRoles);

        if (empty($validRoleNames)) {
            throw new \InvalidArgumentException('No valid roles provided');
        }

        // Get all valid roles in one query to minimize DB calls
        $roles = Role::whereIn('name', $validRoleNames)->get();

        // Create an array of role IDs
        $roleIds = $roles->pluck('role_id')->toArray();

        // Attach roles to user
        $user->roles()->attach($roleIds);
    }

    /**
     * Check if a user has specific role(s).
     *
     * @param User $user
     * @param string|array $roles
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole(User $user, $roles, bool $requireAll = false): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        $userRoles = $user->roles()->pluck('name')->toArray();

        // Check based on requirement (any or all)
        return $requireAll
            ? count(array_intersect($roles, $userRoles)) === count($roles)
            : count(array_intersect($roles, $userRoles)) > 0;
    }

    /**
     * Get available roles for registration.
     *
     * @return array
     */
    public function getRegistrationRoles(): array
    {
        return [
            'buyer' => 'Handgemaakte producten kopen',
            'maker' => 'Verkoop je handgemaakte producten',
        ];
    }
}
