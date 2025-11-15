<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Role
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $adminRoutes = [
            'admin-dashboard',
            'admin-members-add',
            'admin-members-edit',
            'admin-members-store',
            'admin-members-update',
            'admin-members-index',
            'admin-members-destroy',

            'admin-point-add',
            'admin-point-edit',
            'admin-point-store',
            'admin-point-update',
            'admin-point-index',
            'admin-point-destroy',

            'admin-rewards-index',
            'admin-rewards-create',
            'admin-rewards-store',
            'admin-rewards-edit',
            'admin-rewards-update',
            'admin-rewards-delete',

            'admin-redeem-index',
            'admin-redeem-update',

            'register-form',
            'register-user',
            'index-user',
            'edit-user',
            'delete-user',
            'update-user',
            'logout',
            'search-member',
            'admin-change-password',
            'admin-update-password',

            'admin-manager-add',
            'admin-manager-edit',
            'admin-manager-store',
            'admin-manager-update',
            'admin-manager-index',
            'admin-manager-destroy',

            // ⭐ ADD GALLERY ROUTES HERE
              'admin.gallery.index',
    'admin.gallery.create',
    'admin.gallery.store',
    'admin.gallery.destroy',
        ];

        $staffRoutes = [
            'staff-dashboard',
            'members-add',
            'members-edit',
            'members-store',
            'members-index',
            'members-update',
            'logout',
            'staff-point-add',
            'staff-point-edit',
            'staff-point-store',
            'staff-point-update',
            'staff-point-index',
            'staff-point-destroy',
        ];

        $memberRoutes = [
            'member-dashboard',
            'member-points',
            'member-rewards',
            'logout',
        ];

        if ($user->role === 'admin' && !in_array($request->route()->getName(), $adminRoutes)) {
            return redirect()->route('admin-dashboard');
        }

        if ($user->role === 'staff' && !in_array($request->route()->getName(), $staffRoutes)) {
            return redirect()->route('staff-dashboard');
        }

        if ($user->role === 'member' && !in_array($request->route()->getName(), $memberRoutes)) {
            return redirect()->route('member-dashboard');
        }

        return $next($request);
    }
}
