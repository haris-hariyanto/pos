<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\Permission;

class EmailController extends Controller
{
    public function edit(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-email'));
        
        $user = $request->user();

        return view('main.account.account-settings.email', compact('user'));
    }

    public function update(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-email'));

        $user = $request->user();

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($user->email == $request->email) {
            return redirect()->route('account.account-settings.email.edit');
        }
        else {
            $user->update([
                'email' => $request->email,
                'email_verified_at' => null,
            ]);
    
            $user->sendEmailVerificationNotification();
    
            return redirect()->route('account.account-settings.email.edit')->with('success', __('Email has been changed!') . ' ' . __('Verification link has been sent.'));
        }
    }
}
