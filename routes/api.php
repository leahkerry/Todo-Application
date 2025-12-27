<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::apiResource('todos', TodoController::class);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::get('/todos', [TodoController::class, 'index']);
// Route::get('/todos/{id}', [TodoController::class, 'show']);
// Route::post('/todos', [TodoController::class, 'store']);
// Route::put('/todos/{id}', [TodoController::class, 'update']);
// Route::delete('/todos/{id}', [TodoController::class, 'destroy']);

// Protect all todo routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todos', TodoController::class);
});

// NOTE: added as test
// Route::get('/test-api-auth', function () {
//     return response()->json([
//         'authenticated' => auth()->check(),
//         'guard' => auth()->getDefaultDriver(),
//         'user' => auth()->user(),
//         'sanctum_check' => auth('sanctum')->check(),
//         'web_check' => auth('web')->check(),
//     ]);
// });