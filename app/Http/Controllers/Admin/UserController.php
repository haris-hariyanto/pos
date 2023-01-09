<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\Permission;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-index'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Users') => '',
        ];

        $groups = Group::get();

        return view('admin.users.index', compact('breadcrumb', 'groups'));
    }

    public function indexData(Request $request)
    {
        /*
        $counter = 0;
        $queries = [];
        DB::listen(function ($sql) use (&$counter, &$queries) {
            $counter++;
            $queries[] = $sql;
        });
        */

        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-index'));

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');
        $queryGroupID = $request->query('group_id', 0);
        
        $usersCount = User::count();
        
        $users = User::with(['group'])->when($querySearch, function ($query) use ($querySearch) {
            $query->where(function ($subquery) use ($querySearch) {
                $subquery->where('id', $querySearch)
                    ->orWhere('username', 'like', '%' . $querySearch . '%')
                    ->orWhere('email', 'like', '%' . $querySearch . '%');
            });
        })
        ->when($queryGroupID != '0', function ($query) use ($queryGroupID) {
            $query->where('group_id', $queryGroupID);
        });
        $usersCountFiltered = $users->count();

        $users = $users->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();

        return [
        // $respond = [
            'total' => $usersCountFiltered,
            'totalNotFiltered' => $usersCount,
            'rows' => $users->map(function ($user) use ($userAuth) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created_at' => Carbon::parse($user->created_at)->format('Y-m-d H:i:s'),
                    'email_verified_at' => $user->email_verified_at ? __('Verified') : __('Not verified'),
                    'group' => $user->group->name,
                    'menu' => view('admin.users._menu', ['user' => $user, 'userAuth' => $userAuth])->render(),
                ];
            }),
        ];

        // dd($queries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-create'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Users') => route('admin.users.index'),
            __('Create User') => '',
        ];

        $groups = Group::get()->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();

        return view('admin.users.create', compact('breadcrumb', 'groups'));
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
        $this->authorize('auth-check', $userAuth->authorize('admin-users-create'));

        $groups = Group::get()->pluck('id')->toArray();

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:5', 'max:24', 'regex:/^[a-zA-Z0-9_\-\.]+$/', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:32'],
            'group_id' => ['required', Rule::in($groups)],
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'group_id' => $request->group_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', __('User has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-edit'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Users') => route('admin.users.index'),
            __('Edit User') => '',
        ];

        $groups = Group::get()->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();

        return view('admin.users.edit', compact('user', 'breadcrumb', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-edit'));

        $groups = Group::get()->pluck('id')->toArray();

        $validationRules = [
            'username' => ['required', 'string', 'min:5', 'max:24', 'regex:/^[a-zA-Z0-9_\-\.]+$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'group_id' => ['required', Rule::in($groups)],
        ];

        if (Gate::denies('auth-check', $userAuth->authorize('admin-users-group'))) {
            unset($validationRules['group_id']);
        }

        $validated = $request->validate($validationRules);

        $user->update($validated);

        return redirect()->back()->with('success', __('User has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-delete'));

        if ($request->user()->id == $user->id) {
            return redirect()->back()->with('error', __('Can\'t delete your own account!'));
        }

        $user->delete();

        return redirect()->back()->with('success', __('User has been deleted!'));
    }

    public function editPassword(Request $request, User $user)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-password'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Users') => route('admin.users.index'),
            __('Edit Password') => '',
        ];

        return view('admin.users.password', compact('breadcrumb', 'user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-users-password'));

        $request->validate([
            'password' => ['required', 'min:8', 'max:32', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', __('Password has been changed!'));
    }
}
