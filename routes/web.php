<?php

use App\Http\Controllers\ArtisanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/artisan/route-clear', [ArtisanController::class, 'routeClear']);
Route::get('/artisan/route-cache', [ArtisanController::class, 'routeCache']);
Route::get('/artisan/optimize', [ArtisanController::class, 'optimize']);
