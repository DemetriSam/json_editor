<?php

use App\Http\Controllers\RecordController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('records', RecordController::class);
Route::get('records/create/get', [RecordController::class, 'store'])->name('records.store.get');
Route::get('records/update/get', [RecordController::class, 'update'])->name('records.update.get');
