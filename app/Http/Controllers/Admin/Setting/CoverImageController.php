<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\MetaData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class CoverImageController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Home Settings') => '',
        ];

        $continents = Continent::get();

        $homeCoverImages = MetaData::where('key', 'home_cover_images')->first();
        if ($homeCoverImages) {
            $homeCoverImages = $homeCoverImages->value;
            $homeCoverImages = json_decode($homeCoverImages, true);
        }

        return view('admin.settings.home', compact('breadcrumb', 'continents', 'homeCoverImages'));
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
        
        $validatedData = Validator::make($request->all(), $validationRules, [], $fieldNames)->validate();
        $homeCoverImages = json_encode($validatedData);

        MetaData::updateOrCreate(
            ['key' => 'home_cover_images'],
            ['value' => $homeCoverImages]
        );

        Cache::forget('homepage');

        return redirect()->back()->with('success', __('Cover images has been updated!'));
    }
}
