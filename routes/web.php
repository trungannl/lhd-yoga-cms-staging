<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');


Route::middleware('auth')->group(function () {

    Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    Route::get('settings', [SettingController::class, 'index'])->name('setting');

    Route::group(['middleware' => ['permission:staff']], function () {
        Route::resource('staffs', StaffController::class);
        Route::post('staffs/active/{id}', [StaffController::class, 'active'])->name('staffs.active');
        Route::post('staffs/remove-media', [StaffController::class, 'removeMedia'])->name('staffs.remove-media');
    });

    Route::post('uploads/store', [UploadController::class, 'store'])->name('medias.create');

    Route::get('area/district', [AreaController::class, 'district'])->name('area.district');
    Route::get('area/ward', [AreaController::class, 'ward'])->name('area.ward');

//    Route::group(['middleware' => ['permission:setting']], function () {
        Route::resource('roles', RoleController::class);
        Route::post('roles/give-permission-to-role', [RoleController::class, 'givePermissionToRole'])->name('roles.give-permission-to-role');
        Route::post('roles/revoke-permission-to-role', [RoleController::class, 'revokePermissionToRole'])->name('roles.revoke-permission-to-role');
//    });

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');
    Route::post('profile/change', [ProfileController::class, 'change'])->name('profile.change');

    Route::resource('users', UserController::class);
    Route::post('users/active/{id}', [UserController::class, 'active'])->name('users.active');

    Route::resource('studios', StudioController::class);
    Route::post('studios/active/{id}', [StudioController::class, 'active'])->name('studios.active');
    Route::post('studios/cancel/{id}', [StudioController::class, 'cancel'])->name('studios.cancel');
});



