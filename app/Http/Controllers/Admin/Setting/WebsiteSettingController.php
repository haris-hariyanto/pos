<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetaData;
use Illuminate\Support\Facades\Cache;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Website Settings') => '',
        ];

        $settings = MetaData::where('key', 'like', 'settings__%')->get();
        $settings = $settings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        return view('admin.settings.website', compact('breadcrumb', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'settings__website_name' => ['required'],
            'settings__header_script' => ['nullable'],
            'settings__footer_script' => ['nullable'],
            'settings__agoda_suffix' => ['nullable'],
        ]);

        foreach ($validated as $settingName => $settingValue) {
            MetaData::updateOrCreate(
                ['key' => $settingName],
                ['value' => $settingValue]
            );
        }

        $settings = json_encode($validated);
        Cache::forever('websitesettings', $settings);

        return redirect()->back()->with('success', __('Website settings has been updated!'));
    }
}
