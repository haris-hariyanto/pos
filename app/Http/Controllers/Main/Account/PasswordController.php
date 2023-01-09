<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Permission;

class PasswordController extends Controller
{
    public function edit(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-password'));
        
        return view('main.account.account-settings.password');
    }

    public function update(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-password'));

        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'max:32', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.account-settings.password.edit')->with('success', __('Password has been changed!'));
    }
}
