<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Location\Place;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\MetaData;
use Illuminate\Support\Facades\Cache;

class PageSettingConttoller extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Pages Settings') => '',
        ];

        $pages = config('pages');

        $pageExamples = [];
        foreach ($pages as $pageKey => $pageSettings) {
            if ($pageKey == 'home') {
                $pageExamples[$pageKey] = route('index');
            }

            $hotelInstance = Hotel::orderBy('id', 'asc')->first();
            
            if ($pageKey == 'hotel' && $hotelInstance) {
                $pageExamples[$pageKey] = route('hotel', [$hotelInstance->slug]);
            }

            // $placeInstance = Place::where('hotels_nearby', '>', 0)->first();
            $placeInstance = Place::where('id', '>', 0)->first();

            if ($pageKey == 'place' && $placeInstance) {
                $pageExamples[$pageKey] = route('place', [$placeInstance->slug]);
            }

            $continentInstance = Continent::orderBy('id', 'asc')->first();

            if ($pageKey == 'continent' && $placeInstance) {
                $pageExamples[$pageKey] = route('continent', [$continentInstance->slug]);
            }

            $countryInstance = Country::where('name', 'Indonesia')->orWhere('name', 'Thailand')->orderBy('id', 'asc')->first();

            if ($countryInstance) {
                if ($pageKey == 'country') {
                    $pageExamples[$pageKey] = route('country', [$countryInstance->slug]);
                }

                if ($pageKey == 'country_states') {
                    $pageExamples[$pageKey] = route('country.states', [$countryInstance->slug]);
                }

                if ($pageKey == 'country_cities') {
                    $pageExamples[$pageKey] = route('country.cities', [$countryInstance->slug]);
                }

                if ($pageKey == 'country_places') {
                    $pageExamples[$pageKey] = route('country.places', [$countryInstance->slug, config('scraper.place_types_to_fetch')[0]]);
                }
            }

            $cityInstance = City::where('name', 'Jakarta')->orWhere('name', 'Bangkok')->orderBy('id', 'asc')->first();

            if ($pageKey == 'city' && $cityInstance) {
                $pageExamples[$pageKey] = route('hotel.location', ['city', $cityInstance->slug]);
            }

            $stateInstance = State::orderBy('id', 'asc')->first();

            if ($pageKey == 'state' && $stateInstance) {
                $pageExamples[$pageKey] = route('hotel.location', ['state', $stateInstance->slug]);
            }
        }

        $pageSettings = MetaData::where('key', 'like', 'pagesettings__%')->get();
        $pageSettings = $pageSettings->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        });

        return view('admin.settings.pages', compact('breadcrumb', 'pages', 'pageExamples', 'pageSettings'));
    }

    public function save(Request $request)
    {
        $validationRules = [];

        $pages = config('pages');
        foreach ($pages as $pageKey => $page) {
            foreach ($page['fields'] as $field) {
                $validationRules['pagesettings_' . $pageKey . '_' . $field] = ['required'];
            }
        }

        $validated = $request->validate($validationRules);

        $pageSettings = MetaData::where('key', 'like', 'pagesettings__%')->get();
        $pageSettings = $pageSettings->mapWithKeys(function ($item) {
            return [$item->key => $item->id];
        });

        $dataToSave = [];
        foreach ($validated as $dataKey => $dataValue) {
            $dataToSave[] = [
                'id' => $pageSettings[$dataKey],
                'key' => $dataKey,
                'value' => $dataValue,
            ];
        }

        MetaData::upsert($dataToSave, ['key', 'id'], ['value']);

        Cache::forget('pagesettings');

        return redirect()->back()->with('success', __('Pages settings has been updated!'));
    }
}
