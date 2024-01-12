<?php

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

use App\Http\Controllers\VideoProviderController;

Route::get('/', function () {
    return view('provider');
});

Route::get('/join', function () {
    return view('join');
});

Route::get('/provider/{name}', [VideoProviderController::class, 'selectProvider']);
Route::get('/provider/vonage/join/{sessionId}', [VideoProviderController::class, 'joinVonage']);
