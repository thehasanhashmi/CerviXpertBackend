<?php

use App\Http\Controllers\CvmiStagesDetailsController;
use App\Http\Controllers\CvmiTestController;
use App\Http\Controllers\PaymentSettingsController;
use App\Http\Controllers\SubscriptionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Users routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

Route::post('/sendOtp', [UserController::class, 'sendOtp']);

Route::post('/loginWithMobileNumber', [UserController::class, 'loginWithMobileNumber']);

// Subcriptions Routes

Route::resource('subcriptions', SubscriptionsController::class);

Route::post('uploadImage', [SubscriptionsController::class, 'imageUpload']);

Route::get('getSubscriptionbyUserID/{id}', [SubscriptionsController::class, 'getSubscriptionbyUserID']);


// Cvmi Test Routes

Route::resource('cvmiTest', CvmiTestController::class);

Route::post('updatedata/{id}', [CvmiTestController::class, 'updatedata']);

Route::get('getCvmiTestByUserId/{id}', [CvmiTestController::class,'getCvmiTestByUserId']);

Route::post('updateByTestId/{id}', [CvmiTestController::class,'updateByTestId']);



// Cvmi Stages Details Routes


Route::resource('cvmiStagesDetails', CvmiStagesDetailsController::class);

Route::post('updateData/{id}', [CvmiStagesDetailsController::class, 'updateData']);

Route::post('cvmiStagesDetails/storeData', [CvmiStagesDetailsController::class, 'storeData']);



// Payment Settings Routes

Route::resource('paymentsettings', PaymentSettingsController::class);
