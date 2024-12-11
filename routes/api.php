<?php
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\TreesController;
use App\Http\Controllers\TUpdatesController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\UsersController;
Route::get('get-sold-trees', [TreesController::class, 'getSoldTrees']);
Route::get('get-available-trees', [TreesController::class, 'getAvailableTrees']);
Route::get('get-tree-updates-by-id/{treeId}', [TUpdatesController::class, 'getTreeUpdateById']);
Route::get('get-specie/{id}', [SpeciesController::class, 'getSpeciesById']);
Route::get('get-tree/{id}', [TreesController::class, 'getTreesById']);
Route::get('get-all-species', [SpeciesController::class, 'getAllSpecies']);
Route::get('get-friends-count', [UsersController::class, 'friendsCount']);
Route::get('all-available-trees', [TreesController::class, 'getAllAvailableTrees']);//trae todos los arboles disponibles
Route::post('add-new-user', [UsersController::class, 'addNewFriendUser']);
Route::post('create-specie', [SpeciesController::class, 'createSpecie']);
Route::post('create-tree', [TreesController::class, 'createTree']);
Route::put('update-specie/{id}', [SpeciesController::class, 'updateSpecie']);
Route::put('edit-tree/{id}', [TreesController::class, 'updateTree']);
Route::post('auth-user', [UsersController::class, 'authUser']);
Route::delete('delete-specie/{id}', [SpeciesController::class, 'deleteSpecie']);
Route::post('create-user', [UsersController::class, 'createUser']);
Route::delete('delete-tree/{id}', [TreesController::class, 'deleteTree']);
Route::get('get-friends', [UsersController::class, 'getFriends']);
Route::get('get-trees-by-owner/{ownerId}', [TreesController::class, 'getTreesByOwner']);
Route::post('update-tree/{id}', [TreesController::class, 'updateTrees']);
Route::get('authUser', [UsersController::class, 'authUser']);
Route::get('get-updates', [TUpdatesController::class, 'getAllUpdates']);
Route::get('get-sold-trees-with-user', [TreesController::class,'getSoldTreesWithOwnerAndSpecies']);
Route::post('buy-tree/{treeId}', [TreesController::class, 'buyTree']);


