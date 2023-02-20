<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Controllers\Main\HomeController::class, 'index'])->name('index');

if (config('app.locale') == 'id') {
    Route::get('/benua/{continent}', [Controllers\Main\Content\ContinentController::class, 'index'])->name('continent');
    Route::get('/benua', function () {
        return redirect()->route('index');
    });
    
    Route::get('/negara/{country}', [Controllers\Main\Content\CountryController::class, 'index'])->name('country');
    Route::get('/negara/{country}/kota', [Controllers\Main\Content\CountryController::class, 'cities'])->name('country.cities');
    Route::get('/negara/{country}/provinsi', [Controllers\Main\Content\CountryController::class, 'states'])->name('country.states');
    Route::get('/negara/{country}/tempat/{place}', [Controllers\Main\Content\CountryController::class, 'places'])->name('country.places');
    Route::get('/negara', function () {
        return redirect()->route('index');
    });
    
    Route::get('/tempat/{place}', [Controllers\Main\Content\PlaceController::class, 'index'])->name('place');
    Route::get('/tempat', function () {
        return redirect()->route('index');
    });
    
    Route::get('/hotel/{hotel}', [Controllers\Main\Content\HotelController::class, 'index'])->name('hotel');
    Route::get('/hotel/{type}/{location}', [Controllers\Main\Content\LocationController::class, 'index'])->name('hotel.location');
    Route::get('/hotel', function () {
        return redirect()->route('index');
    });

    Route::get('/cari-tempat', [Controllers\Main\Content\SearchController::class, 'searchPlaces'])->name('search.places');
    Route::get('/cari-hotel', [Controllers\Main\Content\SearchController::class, 'searchHotels'])->name('search.hotels');
}
else {
    Route::get('/continent/{continent}', [Controllers\Main\Content\ContinentController::class, 'index'])->name('continent');
    Route::get('/continent', function () {
        return redirect()->route('index');
    });
    
    Route::get('/country/{country}', [Controllers\Main\Content\CountryController::class, 'index'])->name('country');
    Route::get('/country/{country}/cities', [Controllers\Main\Content\CountryController::class, 'cities'])->name('country.cities');
    Route::get('/country/{country}/states', [Controllers\Main\Content\CountryController::class, 'states'])->name('country.states');
    Route::get('/country/{country}/places/{place}', [Controllers\Main\Content\CountryController::class, 'places'])->name('country.places');
    Route::get('/country', function () {
        return redirect()->route('index');
    });
    
    Route::get('/place/{place}', [Controllers\Main\Content\PlaceController::class, 'index'])->name('place');
    Route::get('/place', function () {
        return redirect()->route('index');
    });
    
    Route::get('/hotel/{hotel}', [Controllers\Main\Content\HotelController::class, 'index'])->name('hotel');
    Route::get('/hotel/{type}/{location}', [Controllers\Main\Content\LocationController::class, 'index'])->name('hotel.location');
    Route::get('/hotel', function () {
        return redirect()->route('index');
    });

    Route::get('/search-places', [Controllers\Main\Content\SearchController::class, 'searchPlaces'])->name('search.places');
    Route::get('/search-hotels', [Controllers\Main\Content\SearchController::class, 'searchHotels'])->name('search.hotels');
}

Route::get('/search', [Controllers\Main\Content\SearchController::class, 'index'])->name('search');
Route::get('/search-autocomplete', [Controllers\Main\Content\SearchController::class, 'autocomplete'])->name('search.autocomplete');
Route::post('/hotel/{hotel}/review', [Controllers\Main\Content\ReviewController::class, 'store'])->name('reviews.store');
Route::post('/hotel/{hotel}/reply', [Controllers\Main\Content\ReviewController::class, 'reply'])->name('reviews.reply');

Route::get('/p/{page}', [Controllers\Main\Misc\PageController::class, 'page'])->name('page');
Route::get('/contact', [Controllers\Main\Misc\ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [Controllers\Main\Misc\ContactController::class, 'send']);

Route::get('/sitemaps-index.xml', [Controllers\Main\Misc\SitemapController::class, 'index']);
Route::get('/sitemap-continents-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapContinents'])->name('sitemap.continents');
Route::get('/sitemap-countries-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapCountries'])->name('sitemap.countries');
Route::get('/sitemap-states-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapStates'])->name('sitemap.states');
Route::get('/sitemap-cities-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapCities'])->name('sitemap.cities');
Route::get('/sitemap-places-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapPlaces'])->name('sitemap.places');
Route::get('/sitemap-hotels-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapHotels'])->name('sitemap.hotels');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';