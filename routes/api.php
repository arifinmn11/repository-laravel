<?php

use App\Http\Controllers\Api\v1\AuthControllerApi;
use App\Http\Controllers\Api\v1\BranchControllerApi;
use App\Http\Controllers\Api\v1\PermissionControllerApi;
use App\Http\Controllers\Api\v1\RoleControllerApi;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::get('/test', function () {
        return 'v1';
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::post('/auth/login', [AuthControllerApi::class, 'login'])->name('auth.login');
    Route::post('/auth/register', [AuthControllerApi::class, 'register'])->name('auth.register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::match(['put','patch'], '/auth/role/{id}', [AuthControllerApi::class, 'updateUserRole'])->name('auth.update-user-role');

        Route::post('role', [RoleControllerApi::class, 'create'])->name('role.create');
        Route::get('role/{id}', [RoleControllerApi::class, 'show'])->name('role.show');
        Route::match(['put', 'patch'], 'role/{id}', [RoleControllerApi::class, 'update'])->name('role.update');

        Route::get('/branch', [BranchControllerApi::class, 'index'])->name('branch.index')
        ->middleware('permission:branch-index');
        Route::post('/branch', [BranchControllerApi::class, 'store'])->name('branch.store');
        Route::get('/branch_options', [BranchControllerApi::class, 'options'])->name('branch.options');
        Route::match(['put', 'patch'], 'branch/{id}', [BranchControllerApi::class, 'update'])->name('branch.update');
        Route::delete('/branch/{id}', [BranchControllerApi::class, 'destroy'])->name('branch.destroy');
        Route::get('/branch/{id}', [BranchControllerApi::class, 'show'])->name('branch.show');


        // Route::get('/permission', [PermissionControllerApi::class, 'index'])->name('permission.index');
        Route::post('/permission', [PermissionControllerApi::class, 'store'])->name('permission.store');
        // Route::get('/permission_options', [PermissionControllerApi::class, 'options'])->name('permission.options');
        // Route::match(['put', 'patch'], 'permission/{id}', [PermissionControllerApi::class, 'update'])->name('permission.update');
        // Route::delete('/permission/{id}', [PermissionControllerApi::class, 'destroy'])->name('permission.destroy');
        // Route::get('/permission/{id}', [PermissionControllerApi::class, 'show'])->name('permission.show');
    });
});

Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'error' => 'Not Found',
        'message' => 'The requested URL was not found on this server.',
        'code' => '404',
        'data' => null
    ], 405);
});
