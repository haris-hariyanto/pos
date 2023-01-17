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

    Route::get('/avatar', [Controllers\Main\Account\AvatarController::class, 'edit'])->name('avatar.edit');
    Route::get('/avatar/crop', [Controllers\Main\Account\AvatarController::class, 'edit']);
    
    Route::post('/avatar/crop', [Controllers\Main\Account\AvatarController::class, 'crop'])->name('avatar.crop');
    Route::post('/avatar', [Controllers\Main\Account\AvatarController::class, 'update'])->name('avatar.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:is-admin'])->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    Route::resource('users', Controllers\Admin\UserController::class);
    Route::get('/users-index.json', [Controllers\Admin\UserController::class, 'indexData'])->name('users.index.data');
    Route::get('/users/{user}/password', [Controllers\Admin\UserController::class, 'editPassword'])->name('users.password.edit');
    Route::put('/users/{user}/password', [Controllers\Admin\UserController::class, 'updatePassword'])->name('users.password.update');

    Route::resource('groups', Controllers\Admin\GroupController::class);
    Route::get('/groups-index.json', [Controllers\Admin\GroupController::class, 'indexData'])->name('groups.index.data');

    Route::resource('admins', Controllers\Admin\AdminController::class)->parameters([
        'admins' => 'group',
    ]);
    Route::get('/admins-index.json', [Controllers\Admin\AdminController::class, 'indexData'])->name('admins.index.data');

    Route::get('/groups/{group}/member-permissions', [Controllers\Admin\PermissionController::class, 'editMemberPermissions'])->name('groups.member-permissions.edit');
    Route::put('/groups/{group}/member-permissions', [Controllers\Admin\PermissionController::class, 'updateMemberPermissions'])->name('groups.member-permissions.update');
    Route::get('/groups/{group}/admin-permissions', [Controllers\Admin\PermissionController::class, 'editAdminPermissions'])->name('groups.admin-permissions.edit');
    Route::put('/groups/{group}/admin-permissions', [Controllers\Admin\PermissionController::class, 'updateAdminPermissions'])->name('groups.admin-permissions.update');

    Route::resource('pages', Controllers\Admin\PageController::class);
    Route::get('/pages-index.json', [Controllers\Admin\PageController::class, 'indexData'])->name('pages.index.data');

    Route::resource('contacts', Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('/contacts-index.json', [Controllers\Admin\ContactController::class, 'indexData'])->name('contacts.index.data');
    Route::put('/contacts/{contact}/toggle-status', [Controllers\Admin\ContactController::class, 'toggleStatus'])->name('contacts.toggle-status');
});