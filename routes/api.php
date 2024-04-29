<?php

use App\Enums\TokenEnum;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user('sanctum');
});

/* Create */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::CREATE->value]], function () {
    Route::post('/units', [UnitController::class, 'store']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/locations', [LocationController::class, 'store']);
    Route::post('/categories', [CategoryController::class, 'store']);
});

/* Update */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::UPDATE->value]], function () {
    Route::put('/units/{unit}', [UnitController::class, 'update']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::put('/locations/{location}', [LocationController::class, 'update']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
});

/* Destroy */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::DESTROY->value]], function () {
    Route::delete('/units/{unit}', [UnitController::class, 'destroy']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::delete('/locations/{location}', [LocationController::class, 'destroy']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

/* CRUD Roles & Users */
/* Create */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::CREATE_ROLES_AND_USERS->value]], function () {
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/roles', [RoleController::class, 'store']);
});

/* Read - Get */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::CREATE_ROLES_AND_USERS->value]], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/location/{location}', [UserController::class, 'getUsersByLocation']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{role}', [RoleController::class, 'show']);
});

/* Update */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::CREATE_ROLES_AND_USERS->value]], function () {
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
});

/* Destroy */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::CREATE_ROLES_AND_USERS->value]], function () {
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
});

/* Refresh token */
Route::group(['middleware' => ['auth:sanctum', 'ability:' . TokenEnum::ISSUE_TOKENS->value]], function () {
    Route::post('/auth/refresh', [UserController::class, 'refresh']);
});


/* Unprotected Routes */

/* Read - Get */
Route::get('/units', [UnitController::class, 'index']);
Route::get('/units/{unit}', [UnitController::class, 'show']);
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{location}', [LocationController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/location/{location}', [ProductController::class, 'getProductsByLocation']);

/* Search */
Route::get('/products/barcode/{barcode}', [ProductController::class, 'barcodeSearch']);
Route::get('/categories/search/{name}', [CategoryController::class, 'search']);
Route::get('/locations/search/{name}', [LocationController::class, 'search']);
Route::get('/products/search/{name}', [ProductController::class, 'search']);

/* Authorization & Authentication */
Route::post('/auth/login', [UserController::class, 'login'])->name('login');
Route::post('/auth/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/auth/register', [UserController::class, 'register'])->name('register');
Route::get('/auth/ability/{ability}', [UserController::class, 'checkAbility'])->name('ability');
