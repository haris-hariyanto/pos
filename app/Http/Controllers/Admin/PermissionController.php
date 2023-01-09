<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupsPermission;
use App\Helpers\Permission;

class PermissionController extends Controller
{
    public function editMemberPermissions(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-permissions'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Groups') => route('admin.groups.index'),
            __('Edit Permissions') => '',
        ];

        $permissions = $group->groups_permissions()->where('type', 'member')->pluck('permission')->toArray();

        $permissionsCategory = collect(config('permissions.member'))
            ->map(function ($item, $key) use ($permissions) {
                $isChecked = true;
                foreach ($item as $permission) {
                    if (!in_array($permission, $permissions)) {
                        $isChecked = false;
                        break;
                    }
                }
                return $isChecked;
            })
            ->toArray();
        
        // dd($permissionsCategory);

        return view('admin.permissions.member-permissions', compact('breadcrumb', 'group', 'permissions', 'permissionsCategory'));
    }

    public function updateMemberPermissions(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-permissions'));

        $isRestricted = $request->is_restricted == 'N' ? 'N' : 'Y';

        $group->update([
            'is_member_restricted' => $isRestricted,
        ]);

        $permissionsList = collect(config('permissions.member'));
        $permissionsList = $permissionsList
            ->flatten()
            ->toArray();

        $permissions = $request->permissions;

        $group->groups_permissions()->where('type', 'member')->delete();

        if ($isRestricted == 'Y' && is_array($permissions)) {
            $permissions = collect($permissions)
                ->filter(function ($value) use ($permissionsList) {
                    return in_array($value, $permissionsList);
                })
                ->map(function ($item) {
                    return [
                        'permission' => $item,
                        'type' => 'member',
                    ];
                })
                ->toArray();

            $group->groups_permissions()->createMany($permissions);
        }

        return redirect()->back()->with('success', __('Permissions have been updated!'));
    }

    public function editAdminPermissions(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-admins-permissions'));

        if ($group->is_admin == 'N') {
            abort(404);
        }

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Administrators') => route('admin.admins.index'),
            __('Edit Permissions') => '',
        ];

        $permissions = $group->groups_permissions()->where('type', 'admin')->pluck('permission')->toArray();

        $permissionsCategory = collect(config('permissions.admin'))
            ->map(function ($item, $key) use ($permissions) {
                $isChecked = true;
                foreach ($item as $permission) {
                    if (!in_array($permission, $permissions)) {
                        $isChecked = false;
                        break;
                    }
                }
                return $isChecked;
            })
            ->toArray();

        return view('admin.permissions.admin-permissions', compact('breadcrumb', 'group', 'permissions', 'permissionsCategory'));
    }

    public function updateAdminPermissions(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-admins-permissions'));

        if ($group->is_admin == 'N') {
            abort(404);
        }

        $isRestricted = $request->is_restricted == 'N' ? 'N' : 'Y';

        $group->update([
            'is_admin_restricted' => $isRestricted,
        ]);

        $permissionsList = collect(config('permissions.admin'));
        $permissionsList = $permissionsList
            ->flatten()
            ->toArray();

        $permissions = $request->permissions;

        $group->groups_permissions()->where('type', 'admin')->delete();

        if ($isRestricted == 'Y' && is_array($permissions)) {
            $permissions = collect($permissions)
                ->filter(function ($value) use ($permissionsList) {
                    return in_array($value, $permissionsList);
                })
                ->map(function ($item) {
                    return [
                        'permission' => $item,
                        'type' => 'admin',
                    ];
                })
                ->toArray();
            
            $group->groups_permissions()->createMany($permissions);
        }

        return redirect()->back()->with('success', __('Permissions have been updated!'));
    }
}
