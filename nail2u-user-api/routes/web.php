<?php

use App\Events\JobPostEvent;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('dispath-event', function() {
    event(new JobPostEvent('User has posted a job'));
});
Route::prefix('auth')->group(function () {
//
//    Route::get('login/{provider}', [AuthController::class, 'redirectToProvider']);
//    Route::get('{provider}/callback', [AuthController::class, 'handleProviderCallback']);

});
