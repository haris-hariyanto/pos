<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Carbon;
use App\Helpers\Permission;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-admins-index'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Administrators') => '',
        ];

        $groupsNotAdmin = Group::notAdmin()->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();

        return view('admin.admins.index', compact('breadcrumb', 'groupsNotAdmin'));
    }

    public function indexData(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-admins-index'));

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $groupsCount = Group::admin()->count();

        $groups = Group::admin()->when($querySearch, function ($query) use ($querySearch) {
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
                    'menu' => view('admin.admins._menu', ['group' => $group, 'userAuth' => $userAuth])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('admin.admins.index');
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
        $this->authorize('auth-check', $userAuth->authorize('admin-admins-create'));

        $groupID = $request->group_id;
        $groupsNotAdmin = Group::notAdmin()->get()->pluck('id')->toArray();
        
        if (in_array($groupID, $groupsNotAdmin)) {
            Group::where('id', $groupID)->update([
                'is_admin' => 'Y',
            ]);
            return redirect()->route('admin.admins.index')->with('success', __('Group has been added as administrator!'));
        }

        return redirect()->route('admin.admins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('admin.admins.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return redirect()->route('admin.admins.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-admins-delete'));

        if ($group->id == '1') {
            return redirect()->route('admin.admins.index')->with('error', __('Can\'t delete group from administrator!'));
        }

        $group->update([
            'is_admin' => 'N',
        ]);

        $group->groups_permissions()->where('type', 'admin')->delete();

        return redirect()->route('admin.admins.index')->with('success', __('The group has been removed from the administrator!'));
    }
}
