<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('main.account.account-settings.index');
    }
}
