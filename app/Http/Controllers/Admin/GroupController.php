<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\Permission;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-index'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Groups') => '',
        ];

        return view('admin.groups.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-index'));

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $groupsCount = Group::count();

        $groups = Group::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%');
        });
        $groupsCountFiltered = $groups->count();

        $groups = $groups->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $groupsCountFiltered,
            'totalNotFiltered' => $groupsCount,
            'rows' => $groups->map(function ($group) use ($userAuth) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'created_at' => Carbon::parse($group->created_at)->format('Y-m-d H:i:s'),
                    'menu' => view('admin.groups._menu', ['group' => $group, 'userAuth' => $userAuth])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-create'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Groups') => route('admin.groups.index'),
            __('Create Group') => '',
        ];

        return view('admin.groups.create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-create'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:64', 'regex:/^[a-zA-Z0-9\-\s]+$/'],
        ]);

        Group::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.groups.index')->with('success', __('Group has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return redirect()->route('admin.groups.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-edit'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Groups') => route('admin.groups.index'),
            __('Edit Group') => '',
        ];

        return view('admin.groups.edit', compact('group', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-edit'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:64', 'regex:/^[a-zA-Z0-9\-\s]+$/'],
        ]);

        $group->update($validated);

        return redirect()->back()->with('success', __('Group has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-groups-delete'));

        $groupUsersCount = $group->users()->count();

        if ($groupUsersCount > 0) {
            return redirect()->route('admin.groups.index')->with('error', __('Cannot delete a group that still has a user!'));
        }

        if ($group->is_removable == 'Y') {
            $group->delete();

            return redirect()->route('admin.groups.index')->with('success', __('Group has been deleted!'));
        }

        return redirect()->route('admin.groups.index')->with('error', __('Groups cannot be deleted!'));
    }
}
