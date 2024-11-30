<?php
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\TreesController;
use App\Http\Controllers\UsersController;
Route::get('get-sold-trees', [TreesController::class, 'getSoldTrees']);
Route::post('add-new-user', [UsersController::class, 'addNewFriendUser']);

