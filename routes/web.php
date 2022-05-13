<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrelloBoard;
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

Route::get('/', [TrelloBoard::class, 'index']);
Route::get('/export', [TrelloBoard::class, 'dbBackup']);
