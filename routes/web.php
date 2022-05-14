<?php

use App\Http\Controllers\UserAuthenticationController;
use Illuminate\Support\Facades\Route;
use Tests\Feature\UserAuthenticationTest;

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
    return view('welcome');
});

Route::prefix('user')->group(function(){

    Route::controller(UserAuthenticationController::class)->group(function(){
        Route::get('login','login')->name('user.login');
        Route::post('login','authenticate')->name('user.authenticate');
    });

    Route::middleware('auth')->group(function(){
        Route::get('/', [UserAuthenticationController::class, 'dashboard'])->name('user.dashboard');
    });

});
