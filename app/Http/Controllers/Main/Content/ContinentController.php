<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Helpers\CacheSystemDB;

class ContinentController extends Controller
{
    public function index($continent)
    {
        $cacheKey = 'continent' . $continent;
        $cacheData = CacheSystemDB::get($cacheKey);

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
            $cacheTags = [];
            $cacheTags[] = '[continent:' . $continent['id'] . ']';
            foreach ($continent['countries'] as $country) {
                $cacheTags[] = '[country:' . $country['id'] . ']';
            }
            CacheSystemDB::generate($cacheKey, compact('continent'), [], $cacheTags);
            // [END] Generate cache
        }

        return view('main.contents.continent', compact('continent'));
    }
}
