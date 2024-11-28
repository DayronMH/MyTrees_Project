<?php
use Illuminate\Support\Facades\Route;
// Rutas de la aplicación
Route::get('/', fn() => view('app'));
Route::get('/login', fn() => view('app'));
Route::get('/register', fn() => view('app'));