<?php
use Illuminate\Support\Facades\Route;

// Ruta para la página principal
Route::get('/', function () {
    return view('app');
});

// Ruta para capturar todas las demás rutas (SPA)
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');

