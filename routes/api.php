<?php

use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\BranchControllerApi;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth/login', [AuthControllerApi::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/branch', [BranchControllerApi::class, 'index'])->name('branch.index');
    Route::post('/branch', [BranchControllerApi::class, 'store'])->name('branch.store');
    Route::get('/branch/options', [BranchControllerApi::class, 'options'])->name('branch.options');
    Route::match(['put', 'patch'], 'branch/{id}', [BranchControllerApi::class, 'update'])->name('branch.update');
    Route::delete('/branch/{id}', [BranchControllerApi::class, 'destroy'])->name('branch.destroy');
    Route::get('/branch/{id}', [BranchControllerApi::class, 'show'])->name('branch.show');
});


Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'error' => 'Not Found',
        'message' => 'The requested URL was not found on this server.',
        'code' => '404',
        'data' => null
    ], 404);
});
