<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\Permission;

class UsernameController extends Controller
{
    public function edit(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-username'));
        
        $user = $request->user();

        return view('main.account.account-settings.username', compact('user'));
    }

    public function update(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-username'));

        $user = $request->user();

        $request->validate([
            'username' => ['required', 'string', 'min:5', 'max:24', 'regex:/^[a-zA-Z0-9_\-\.]+$/', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($user->username == $request->username) {
            return redirect()->route('account.account-settings.username.edit');
        }
        else {
            $user->update([
                'username' => $request->username,
            ]);
    
            return redirect()->route('account.account-settings.username.edit')->with('success', __('Username has been changed!'));
        }
    }
}
