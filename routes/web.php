<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// NOTE: added
use App\Http\Controllers\TodoController;

// Route::get('/', function () {
//     return Inertia::render('TodoApp');
// })->name('home');;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
})->middleware(['auth'])->name('dashboard');
// NOTE: do you need verified above?

// NOTE: added
Route::middleware(['auth'])->group(function () {
    Route::get('/todos', [TodoController::class, 'index']);
});
// Route::middleware(['auth'])->group(function () {
//     Route::get('/todos', function () {
//         return Inertia::render('TodoApp');
//     });
// });


Route::get('/test-auth', function () {
    return [
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
    ];
})->middleware('web');

require __DIR__.'/settings.php';
