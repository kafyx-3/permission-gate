<?php

namespace Kafyx3\PermissionGate;

use Illuminate\Support\Facades\Gate;

class PermissionGate
{
    /**
     * Used by @can directive.
     *
     * If $rolesOrModel is an array, check user role against it.
     * Otherwise fall back to Laravel's Gate (supports policies too).
     */
    public static function can(string $ability, mixed $rolesOrModel = null): bool
    {
        if (is_array($rolesOrModel)) {
            if (! auth()->check()) {
                return false;
            }
            return in_array(auth()->user()->role, $rolesOrModel, strict: true);
        }

        return Gate::check($ability, array_filter([$rolesOrModel]));
    }

    /**
     * Used by @hasRole directive.
     *
     * Accepts mixed args: @hasRole('admin', 'petugas') or @hasRole(['admin', 'petugas'])
     */
    public static function hasRole(string|array ...$roles): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $flat = array_merge(...array_map(
            fn($r) => is_array($r) ? $r : [$r],
            $roles
        ));

        return in_array(auth()->user()->role, $flat, strict: true);
    }
}
