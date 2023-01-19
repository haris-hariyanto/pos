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

Route::get('/continent/{continent}', [Controllers\Main\Content\ContinentController::class, 'index'])->name('continent');
Route::get('/country/{country}', [Controllers\Main\Content\CountryController::class, 'index'])->name('country');
Route::get('/country/{country}/cities', [Controllers\Main\Content\CountryController::class, 'cities'])->name('country.cities');
Route::get('/country/{country}/states', [Controllers\Main\Content\CountryController::class, 'states'])->name('country.states');
Route::get('/country/{country}/places/{place}', [Controllers\Main\Content\CountryController::class, 'places'])->name('country.places');
Route::get('/place/{place}', [Controllers\Main\Content\PlaceController::class, 'index'])->name('place');
Route::get('/hotel/{hotel}', [Controllers\Main\Content\HotelController::class, 'index'])->name('hotel');
Route::get('/hotel/{type}/{location}', [Controllers\Main\Content\LocationController::class, 'index'])->name('hotel.location');

Route::get('/p/{page}', [Controllers\Main\Misc\PageController::class, 'page'])->name('page');
Route::get('/contact', [Controllers\Main\Misc\ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [Controllers\Main\Misc\ContactController::class, 'send']);

Route::get('/sitemaps-index.xml', [Controllers\Main\Misc\SitemapController::class, 'index']);
Route::get('/sitemap-sample-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapSample']);

require __DIR__.'/auth.php';

Route::prefix('settings')->name('account.account-settings.')->middleware(['auth'])->group(function () {
    Route::get('/', [Controllers\Main\Account\AccountSettingsController::class, 'index'])->name('index');

    Route::get('/username', [Controllers\Main\Account\UsernameController::class, 'edit'])->name('username.edit');
    Route::put('/username', [Controllers\Main\Account\UsernameController::class, 'update'])->name('username.update');

    Route::get('/email', [Controllers\Main\Account\EmailController::class, 'edit'])->name('email.edit');
    Route::put('/email', [Controllers\Main\Account\EmailController::class, 'update'])->name('email.update');

    Route::get('/password', [Controllers\Main\Account\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [Controllers\Main\Account\PasswordController::class, 'update'])->name('password.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    Route::resource('hotels', Controllers\Admin\Content\HotelController::class);
    Route::get('/hotels-index.json', [Controllers\Admin\Content\HotelController::class, 'indexData'])->name('hotels.index.data');

    Route::resource('places', Controllers\Admin\Content\PlaceController::class);
    Route::get('/places-index.json', [Controllers\Admin\Content\PlaceController::class, 'indexData'])->name('places.index.data');

    Route::resource('cities', Controllers\Admin\Content\CityController::class);
    Route::get('/cities-index.json', [Controllers\Admin\Content\CityController::class, 'indexData'])->name('cities.index.data');

    Route::resource('states', Controllers\Admin\Content\StateController::class);
    Route::get('/states-index.json', [Controllers\Admin\Content\StateController::class, 'indexData'])->name('states.index.data');

    Route::resource('countries', Controllers\Admin\Content\CountryController::class);
    Route::get('/countries-index.json', [Controllers\Admin\Content\CountryController::class, 'indexData'])->name('countries.index.data');

    Route::resource('continents', Controllers\Admin\Content\ContinentController::class);
    Route::get('/continents-index.json', [Controllers\Admin\Content\ContinentController::class, 'indexData'])->name('continents.index.data');

    Route::resource('users', Controllers\Admin\UserController::class);
    Route::get('/users-index.json', [Controllers\Admin\UserController::class, 'indexData'])->name('users.index.data');
    Route::get('/users/{user}/password', [Controllers\Admin\UserController::class, 'editPassword'])->name('users.password.edit');
    Route::put('/users/{user}/password', [Controllers\Admin\UserController::class, 'updatePassword'])->name('users.password.update');

    Route::resource('pages', Controllers\Admin\PageController::class);
    Route::get('/pages-index.json', [Controllers\Admin\PageController::class, 'indexData'])->name('pages.index.data');

    Route::resource('contacts', Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('/contacts-index.json', [Controllers\Admin\ContactController::class, 'indexData'])->name('contacts.index.data');
    Route::put('/contacts/{contact}/toggle-status', [Controllers\Admin\ContactController::class, 'toggleStatus'])->name('contacts.toggle-status');
});