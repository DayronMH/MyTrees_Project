<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreesController;

Route::get('get-sold-trees', [TreesController::class, 'getSoldTrees']);
