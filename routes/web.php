<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MachineController;
use App\Models\Machine;

Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

Route::resource('machines', MachineController::class);

});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

Route::resource('users', UserController::class);
});

Route::get('/chatbot', function () {
    return view('chatbot');
});

Route::post('/chatbot', [ChatbotController::class, 'chat']);


Route::get('/', function () {

    $machines = Machine::all();

    return view('welcome', compact('machines'));

})->name('welcome');

Route::get('/dashboard', function () {

    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
