<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/events',[EventController::class, 'events']);
Route::get('/v1/events/active-events',[EventController::class, 'active_events']);
Route::get('/v1/events/{id}',[EventController::class, 'event_get']);
Route::post('/v1/events',[EventController::class, 'events_create']);
Route::put('/v1/events/{id}',[EventController::class, 'events_put']);
Route::patch('/v1/events/{id}',[EventController::class, 'events_patch']);
Route::delete('/v1/events/{id}',[EventController::class, 'events_delele']);
