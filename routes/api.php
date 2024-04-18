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
Route::resource('users', UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
