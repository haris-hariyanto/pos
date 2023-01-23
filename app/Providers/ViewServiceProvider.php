<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\AdminComposer;
use App\View\Composers\MemberComposer;
use Illuminate\Support\Facades\Cache;
use App\Models\MetaData;

class ViewServiceProvider extends ServiceProvider
{

    public function boot()
    {
        View::composer('*', function ($view) {
            $websiteSettings = Cache::get('websitesettings');
            if ($websiteSettings) {
                $settings = json_decode($websiteSettings, true);
            }
            else {
                $settings = MetaData::where('key', 'like', 'settings__%')->get();
                $settings = $settings->mapWithKeys(function ($item) {
                    return [$item->key => $item->value];
                })->toArray();

                $settingsCache = json_encode($settings);
                Cache::forever('websitesettings', $settingsCache);
            }

            foreach ($settings as $settingKey => $settingValue) {
                $view->with($settingKey, $settingValue);
            }
        });
    }

}