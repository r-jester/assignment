<?php

use Illuminate\Support\Facades\Route;
use Modules\Jester\Http\Controllers\JesterController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('jesters', JesterController::class)->names('jester');
});
