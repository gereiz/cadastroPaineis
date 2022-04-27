<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutdoorController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\OutdoorController::class, 'index'])->name('home');

Route::get('/addOutdoorForm', [App\Http\Controllers\OutdoorController::class, 'addForm'])->name('add_form_outdoor');
Route::post('/insertOutdoor', [App\Http\Controllers\OutdoorController::class, 'store'])->name('insert_outdoor');
Route::get('/outdoorsGrid', [App\Http\Controllers\OutdoorController::class, 'index'])->name('outdoor_grid');
Route::post('/deleteOutdoor/{id}', [App\Http\Controllers\OutdoorController::class, 'delete'])->name('delete_outdoor');

Route::get('/editFormOutdoor/{id}', [App\Http\Controllers\OutdoorController::class, 'editForm'])->name('edit_outdoor');

Route::get('/viewFormOutdoor/{id}', [App\Http\Controllers\OutdoorController::class, 'viewForm'])->name('view_outdoor');

