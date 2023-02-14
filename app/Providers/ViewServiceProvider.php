<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\AdminComposer;
use App\View\Composers\MemberComposer;
use Illuminate\Support\Facades\Cache;
use App\Models\MetaData;
use App\Models\Hotel\Review;

class ViewServiceProvider extends ServiceProvider
{

    public function boot()
    {
        View::composer('*', function ($view) {
            $websiteSettings = Cache::get('websitesettings');
            if (!empty($websiteSettings)) {
                $settings = json_decode($websiteSettings, true);
                if (empty($settings)) {
                    $createCache = true;
                }
                else {
                    $createCache = false;
                }
            }
            else {
                $createCache = true;
            }

            if ($createCache) {
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

        View::composer('admin.*', function ($view) {
            $newReviews = Review::where('is_accepted', 'N')->count();
            $view->with('newReviews', $newReviews);
        });

        View::composer('main.contents.hotel', function ($view) {
            if (Cache::has('reviewssettings')) {
                $settings = json_decode(Cache::get('reviewssettings'), true);
                if (empty($settings)) {
                    $createCache = true;
                }
                else {
                    $createCache = false;
                }
            }
            else {
                $createCache = true;
            }

            if ($createCache) {
                $settings = MetaData::where('key', 'like', 'reviewssettings__%')->get();
                $settings = $settings->mapWithKeys(function ($item) {
                    return [$item->key => $item->value];
                })->toArray();

                $settingsCache = json_encode($settings);
                Cache::forever('reviewssettings', $settingsCache);
            }

            foreach ($settings as $settingKey => $settingValue) {
                $view->with($settingKey, $settingValue);
            }
        });

        View::composer(['main.index', 'main.*'], function ($view) {
            $pageSettings = Cache::get('pagesettings');
            if (!empty($pageSettings)) {
                $settings = json_decode($pageSettings, true);
                if (empty($settings)) {
                    $createCache = true;
                }
                else {
                    $createCache = false;
                }
            }
            else {
                $createCache = true;
            }

            if ($createCache) {
                $settings = MetaData::where('key', 'like', 'pagesettings_%')->get();
                $settings = $settings->mapWithKeys(function ($item) {
                    return [$item->key => $item->value];
                })->toArray();

                $settingsCache = json_encode($settings);
                Cache::forever('pagesettings', $settingsCache);
            }

            foreach ($settings as $settingKey => $settingValue) {
                $view->with($settingKey, $settingValue);
            }
        });
    }

}