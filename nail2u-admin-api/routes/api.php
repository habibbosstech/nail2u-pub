<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider App\Http\Controllers\ServiceControllerwithin a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {

    //Public Routes
    Route::post('login', [AuthController::class, 'login']);
    Route::get('verify-email/{token}/{email}', [AuthController::class, 'verifyEmail']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password/{token}/{email}', [AuthController::class, 'resetPassword']);
    Route::get('verify-email/{token}/{email}', [AuthController::class, 'verifyEmail']);

    Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
        Route::post('add-admin', [AuthController::class, 'addAdmin']);
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('carousel')->group(function () {

    //Public Route
    Route::get('images', [CarouselController::class, 'getAll']);
});

//Services Routes
Route::group(['middleware' => ['auth:api', 'role:admin', 'check-user-status']], function () {

    Route::prefix('dashboard')->group(function () {

        Route::get('get-clients-count', [DashboardController::class, 'getClientsCount']);
        Route::get('get-artists-count', [DashboardController::class, 'getArtistsCount']);
        Route::get('get-bookings-count', [DashboardController::class, 'getBookingsCount']);
        Route::get('get-job-posts-count', [DashboardController::class, 'getJobPostsCount']);
    });

    Route::prefix('carousel')->group(function () {

        Route::post('upload', [CarouselController::class, 'upload']);
        Route::post('update', [CarouselController::class, 'update']);
        Route::get('get-all', [CarouselController::class, 'getAll']);
        Route::post('delete', [CarouselController::class, 'delete']);
    });

    Route::prefix('user')->group(function () {

        Route::post('list-all', [UserController::class, 'listAll']);
        Route::post('get-user-detail', [UserController::class, 'getUserDetail']);
        Route::post('delete', [UserController::class, 'delete']);
    });

    Route::prefix('artist')->group(function () {

        Route::post('list-all', [ArtistController::class, 'listAll']);
        Route::post('delete', [ArtistController::class, 'delete']);
        Route::post('add', [ArtistController::class, 'add']);
    });

    Route::prefix('booking')->group(function () {
        Route::post('list-all', [BookingController::class, 'listAll']);
        Route::post('delete', [BookingController::class, 'delete']);
    });

    Route::prefix('task')->group(function () {
        Route::post('add', [TaskController::class, 'add']);
        Route::post('get-details', [TaskController::class, 'getDetails']);
    });

    Route::prefix('deal')->group(function () {
        Route::post('add-new', [DealController::class, 'addNew']);
        Route::get('list-ongoing', [DealController::class, 'listOngoing']);
        Route::get('list-all', [DealController::class, 'listAll']);
        Route::post('delete', [DealController::class, 'delete']);
        Route::post('edit', [DealController::class, 'edit']);
    });

    Route::prefix('payments')->group(function () {
    });

    Route::prefix('services')->group(function () {
        Route::get('all', [ServicesController::class, 'allServices']);
        Route::post('edit', [ServicesController::class, 'editServices']);
        Route::post('create', [ServicesController::class, 'createServices']);
        Route::post('delete', [ServicesController::class, 'deleteServices']);
    });

    Route::prefix('admins')->group(function () {
        Route::get('all', [AdminController::class, 'allAdmins']);
        Route::post('add', [AdminController::class, 'addAdmin']);
        Route::get('get-details/{id}', [AdminController::class, 'getDetails']);
    });

    Route::prefix('settings')->group(function () {
        Route::post('general-setting', [SettingController::class, 'generalSetting']);
        Route::post('notifications-setting', [SettingController::class, 'notificationsSetting']);
        Route::post('profile-setting', [SettingController::class, 'profileSetting']);
    });
});

Route::any(
    '{any}',
    function () {
        return response()->json([
            'status_code' => 404,
            'message' => 'Page Not Found. Check method type Post/Get or URL',
        ], 404);
    }
)->where('any', '.*');
