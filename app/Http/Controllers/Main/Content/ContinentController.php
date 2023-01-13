<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;

class ContinentController extends Controller
{
    public function index($continent)
    {
        $isCachedData = false;
        if ($isCachedData) {

        }
        else {
            $modelContinent = Continent::with('countries')->where('slug', $continent)->first();
            if (!$modelContinent) {
                return redirect()->route('index');
            }
            $continent = $modelContinent->toArray();

            $countries = [];
            foreach ($modelContinent->countries()->orderBy('name', 'asc')->get() as $country) {
                $countries[] = $country->toArray();
            }
        }

        return view('main.contents.continent', compact('continent', 'countries'));
    }
}
