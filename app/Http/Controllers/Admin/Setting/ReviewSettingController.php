<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetaData;
use Illuminate\Support\Facades\Cache;

class ReviewSettingController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Reviews Settings') => '',
        ];

        return view('admin.settings.reviews', compact('breadcrumb'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'reviewssettings__allow_new_reviews' => ['required', 'in:Y,N'],
        ]);

        foreach ($validated as $settingName => $settingValue) {
            MetaData::updateOrCreate(
                ['key' => $settingName],
                ['value' => $settingValue]
            );
        }

        $settings = json_encode($validated);
        Cache::forever('reviewssettings', $settings);

        return redirect()->back()->with('success', __('Reviews settings has been updated!'));
    }
}
