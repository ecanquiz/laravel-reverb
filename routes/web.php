<?php

use App\Events\HelloWorld;
use App\Events\MessageReceived;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




Route::get('/', function () {
    HelloWorld::dispatch();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post("/messages", function () {
    $payload = request()->validate([
        'message' => 'required',
        'id' => 'required'
    ]);
    MessageReceived::dispatch($payload['message'], $payload['id']);
    return response()->json(['message' => 'message received']);
});

Route::get('visit-count', function () {
    HelloWorld::dispatch();
    return Inertia::render('VisitCount');
    //return Inertia::render('VisitCount', [
    //    "user" => auth()->user()
    //]);

});

require __DIR__.'/auth.php';
