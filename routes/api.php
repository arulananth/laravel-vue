<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrelloBoard;
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

Route::get('get-cols', [TrelloBoard::class, 'getColLists']);
Route::post('store-card', [TrelloBoard::class, 'storeCard']);
Route::post('store-col', [TrelloBoard::class, 'storeCol']);
Route::delete('delete-col/{id}', [TrelloBoard::class, 'deleteCol']);
Route::delete('delete-card/{id}', [TrelloBoard::class, 'deleteCard']);
