<?php
use Illuminate\Support\Facades\Route;
Route::get('/{any}', function () {
    return redirect(env('APP_URL'));
})->where('any', '.*');