<?php

use App\Http\Controllers\Api\BranchControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/branch', [BranchControllerApi::class, 'index']);
Route::post('/branch', [BranchControllerApi::class, 'store']);
Route::get('/branch/options', [BranchControllerApi::class, 'options']);
Route::match(['put', 'patch'], 'branch/{id}', [BranchControllerApi::class, 'update']);
Route::delete('/branch/{id}', [BranchControllerApi::class, 'destroy']);
Route::get('/branch/{id}', [BranchControllerApi::class, 'show']);
