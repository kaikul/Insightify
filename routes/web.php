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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Setting up API Keys with the profile
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
Route::post('/apiToken', [App\Http\Controllers\ProfileController::class, 'saveAPITokens'])->name('apiToken');
Route::get('/post', [App\Http\Controllers\PostController::class, 'postPage'])->name('postPage');
Route::post('/post', [App\Http\Controllers\PostController::class, 'GetPost'])->name('GetPost');
Route::get('/check', [App\Http\Controllers\PostController::class, 'CheckPost'])->name('CheckPost');
Route::get('/sync-instagram-posts', [App\Http\Controllers\PostController::class, 'SyncAll'])->name('SyncAll');


