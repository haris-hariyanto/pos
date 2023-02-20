<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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
        // return view('admin.index');
        return redirect()->route('admin.hotels.index');
    })->name('index');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/cover', [Controllers\Admin\Setting\CoverImageController::class, 'index'])->name('cover');
        Route::put('/cover', [Controllers\Admin\Setting\CoverImageController::class, 'setCoverImages']);

        Route::get('/cache', [Controllers\Admin\Setting\CacheController::class, 'index'])->name('cache');
        Route::delete('/cache', [Controllers\Admin\Setting\CacheController::class, 'flush']);

        Route::get('/website', [Controllers\Admin\Setting\WebsiteSettingController::class, 'index'])->name('website');
        Route::put('/website', [Controllers\Admin\Setting\WebsiteSettingController::class, 'updateSettings']);

        Route::get('/reviews', [Controllers\Admin\Setting\ReviewSettingController::class, 'index'])->name('reviews');
        Route::put('/reviews', [Controllers\Admin\Setting\ReviewSettingController::class, 'save']);

        Route::get('/search', [Controllers\Admin\Setting\SearchController::class, 'index'])->name('search');
        Route::put('/search', [Controllers\Admin\Setting\SearchController::class, 'save'])->name('save');

        Route::get('/pages', [Controllers\Admin\Setting\PageSettingConttoller::class, 'index'])->name('pages');
        Route::put('/pages', [Controllers\Admin\Setting\PageSettingConttoller::class, 'save']);
    });

    Route::resource('hotels', Controllers\Admin\Content\HotelController::class);
    Route::get('/hotels-index.json', [Controllers\Admin\Content\HotelController::class, 'indexData'])->name('hotels.index.data');
    Route::post('/hotels/{hotel}/update-photos', [Controllers\Admin\Content\HotelController::class, 'updatePhotos'])->name('hotels.update-cover');
    Route::get('/hotels/{hotel}/add-photos', [Controllers\Admin\Content\HotelController::class, 'changePhoto'])->name('hotels.add-photo');

    Route::get('/places/find', [Controllers\Admin\Content\PlaceFinderController::class, 'index'])->name('places.find');
    Route::post('/places/find', [Controllers\Admin\Content\PlaceFinderController::class, 'store']);
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

    Route::resource('reviews', Controllers\Admin\Content\ReviewController::class);
    Route::get('/reviews-index.json', [Controllers\Admin\Content\ReviewController::class, 'indexData'])->name('reviews.index.data');
    Route::put('/reviews/{review}/approve', [Controllers\Admin\Content\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::put('/reviews/{review}/unapprove', [Controllers\Admin\Content\ReviewController::class, 'unapprove'])->name('reviews.unapprove');
    Route::post('/reviews/{review}/reply', [Controllers\Admin\Content\ReviewController::class, 'reply'])->name('reviews.reply');

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