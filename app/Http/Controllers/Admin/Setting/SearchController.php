<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetaData;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Search Settings') => '',
        ];

        $settings = MetaData::where('key', 'like', 'searchsettings__%')->get();
        $settings = $settings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        return view('admin.settings.search', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'searchsettings__enabled' => ['required', 'in:Y,N'],
        ]);

        foreach ($validated as $settingName => $settingValue) {
            MetaData::updateOrCreate(
                ['key' => $settingName],
                ['value' => $settingValue],
            );
        }

        $settings = json_encode($validated);
        Cache::forever('searchsettings', $settings);

        return redirect()->back()->with('success', __('Search settings has been updated!'));
    }
}
