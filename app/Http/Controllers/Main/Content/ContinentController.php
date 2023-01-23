<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Helpers\CacheSystem;

class ContinentController extends Controller
{
    public function index($continent)
    {
        $cacheKey = 'continent' . $continent;
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            $modelContinent = Continent::with('countries')->where('slug', $continent)->first();
            if (!$modelContinent) {
                return redirect()->route('index');
            }
            $continent = $modelContinent->toArray();

            // Generate cache
            CacheSystem::generate($cacheKey, compact('continent'));
        }

        return view('main.contents.continent', compact('continent'));
    }
}
