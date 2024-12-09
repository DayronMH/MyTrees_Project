<?php
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('app'));
Route::get('/login', fn() => view('app'));
Route::get('/register', fn() => view('app'));
Route::get('/adminPanel', fn() => view('app'));
Route::get('/friendPanel', fn() => view('app'));
Route::get('/editSpecie', fn() => view('app'));
Route::get('/addSpecie', fn() => view('app'));
Route::get('/addTree', fn() => view('app'));
Route::get('/friendTrees', fn() => view('app'));
Route::get('/editTree', fn() => view('app'));
Route::get('/updateTrees', fn() => view('app'));
Route::get('/operatorPanel', fn() => view('app'));
Route::get('/addUser', fn() => view('app'));