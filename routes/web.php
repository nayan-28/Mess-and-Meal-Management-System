<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('newMember', [ProfileController::class, 'newMember'])->name('newMember');
    Route::post('approvemember',[ProfileController::class,'approvemember'])
    ->name('approvemember');
    Route::post('rejectmember',[ProfileController::class,'rejectmember'])
    ->name('rejectmember');
    Route::get('oldMember', [ProfileController::class, 'oldMember'])
    ->name('oldMember');
    Route::post('removemember',[ProfileController::class,'removemember'])
    ->name('removemember');
    Route::get('payment', [ProfileController::class, 'payment'])
    ->name('payment');
    Route::post('updatedetails', [ProfileController::class, 'updatedetails'])
    ->name('updatedetails');
    Route::post('addcurrentmember', [ProfileController::class, 'addcurrentmember'])
    ->name('addcurrentmember');
    Route::get('mealdetails', [ProfileController::class, 'mealdetails'])
    ->name('mealdetails');
});

require __DIR__.'/auth.php';


//Border//


Route::get('/border/dashboard', function () {
    return view('border.dashboard');
})->middleware(['auth:border', 'verified'])->name('border.dashboard');


require __DIR__.'/borderauth.php';
