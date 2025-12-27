<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
// use App\Http\Controllers\TodoController;


Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/todos', fn () => Inertia::render('TodoApp'))->name('todos');
});

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// // })->middleware(['auth', 'verified'])->name('dashboard');
// })->middleware(['auth'])->name('dashboard');
// NOTE: do you need verified above?

// Route::middleware(['auth'])->group(function () {
//     Route::get('/todos', [TodoController::class, 'index']);
// });
// Route::middleware(['auth'])->group(function () {
//     Route::get('/todos', function () {
//         return Inertia::render('TodoApp');
//     });
// });




require __DIR__.'/settings.php';
