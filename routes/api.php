<?php

use App\Http\Controllers\Api\BranchControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Traits\ApiResponse;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/branch', [BranchControllerApi::class, 'index'])->name('branch.index');
Route::post('/branch', [BranchControllerApi::class, 'store'])->name('branch.store');
Route::get('/branch/options', [BranchControllerApi::class, 'options'])->name('branch.options');
Route::match(['put', 'patch'], 'branch/{id}', [BranchControllerApi::class, 'update'])->name('branch.update');
Route::delete('/branch/{id}', [BranchControllerApi::class, 'destroy'])->name('branch.destroy');
Route::get('/branch/{id}', [BranchControllerApi::class, 'show'])->name('branch.show');


Route::any('*', function () {
    return response()->json(['message' => 'Not Found'], 404);
});
