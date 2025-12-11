<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LinkController;


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

// Create new link
Route::middleware(['api.key', 'throttle:30,1'])->group(function () {
    Route::post('/links', [LinkController::class, 'store']);
    Route::get('/links/{slug}/stats', [LinkController::class, 'stats']);
});
