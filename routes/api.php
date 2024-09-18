<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

Route::get('/roles', [RoleController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);

