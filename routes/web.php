<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/gameEntry', [App\Http\Controllers\GameController::class, 'gameEntry'])->name('gameEntry');
Route::get('/gameDelete', [App\Http\Controllers\GameController::class, 'gameDelete'])->name('gameDelete');
Route::get('/gameAction/{id}', [App\Http\Controllers\GameController::class, 'gameAction'])->name('gameAction');
