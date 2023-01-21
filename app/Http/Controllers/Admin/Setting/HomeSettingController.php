<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use Illuminate\Support\Facades\Validator;

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
        $validationRules = [];
        $fieldNames = [];
        $continents = Continent::get();
        foreach ($continents as $continent) {
            $validationRules[$continent->slug] = ['url'];
            $fieldNames[$continent->slug] = __('Cover image');
        }
        
        Validator::make($request->all(), $validationRules, [], $fieldNames)->validate();
    }
}
