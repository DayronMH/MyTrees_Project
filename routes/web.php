<?php
use Illuminate\Support\Facades\Route;

// Rutas de la aplicación
Route::get('/', fn() => view('app'));
Route::get('/login', fn() => view('app'));
Route::get('/register', fn() => view('app'));

// Ruta comodín para capturar todas las rutas de la SPA
Route::get('/{any?}', fn() => view('app'))->where('any', '.*');