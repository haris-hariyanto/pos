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

        $settings = MetaData::where('key', 'like', 'reviewssettings__%')->get();
        $settings = $settings->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        return view('admin.settings.reviews', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'reviewssettings__allow_new_reviews' => ['required', 'in:Y,N'],
            'reviewssettings__allow_reply_to_reviews' => ['required', 'in:Y,N'],
            'reviewssettings__reviews_must_be_approved' => ['required', 'in:Y,N'],
            'reviewssettings__replies_must_be_approved' => ['required', 'in:Y,N'],
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
