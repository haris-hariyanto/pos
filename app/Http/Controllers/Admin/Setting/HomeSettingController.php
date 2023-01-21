<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;

class HomeSettingController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Home Settings') => '',
        ];

        $continents = Continent::get();

        return view('admin.settings.home', compact('breadcrumb', 'continents'));
    }

    public function setCoverImages(Request $request)
    {
        dd($request->all());
    }
}
