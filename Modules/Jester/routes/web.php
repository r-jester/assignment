<?php

use Illuminate\Support\Facades\Route;
use Modules\Jester\Http\Controllers\ChatController;
use Modules\Jester\Http\Controllers\JesterController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('jesters', JesterController::class)->names('jester');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');
});
