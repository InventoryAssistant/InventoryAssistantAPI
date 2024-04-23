<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('roles', RoleController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('locations', LocationController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('categories', CategoryController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::resource('products', ProductController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::get('/products/location/{location}', [ProductController::class, 'getProductsByLocation']);
Route::resource('users', UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
Route::get('/users/location/{location}', [UserController::class, 'getUsersByLocation']);

Route::get('/categories/search/{name}', [CategoryController::class, 'search']);
Route::get('/locations/search/{name}', [LocationController::class, 'search']);
Route::get('/products/search/{name}', [ProductController::class, 'search']);
