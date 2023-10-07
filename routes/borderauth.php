<?php

use App\Http\Controllers\BorderAuth\BorderAuthenticatedSessionController;
use App\Http\Controllers\BorderAuth\BorderConfirmablePasswordController;
use App\Http\Controllers\BorderAuth\BorderEmailVerificationNotificationController;
use App\Http\Controllers\BorderAuth\BorderPasswordController;
use App\Http\Controllers\BorderAuth\BorderVerifyEmailController;
use App\Http\Controllers\BorderAuth\BorderEmailVerificationPromptController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorderProfileController;
use App\Http\Controllers\BorderAuth\BorderNewPasswordController;
use App\Http\Controllers\BorderAuth\BorderPasswordResetLinkController;
use App\Http\Controllers\BorderAuth\BorderRegisteredUserController;


Route::group(['middleware'=>['guest:border'],'prefix'=>'border','as'=>'border.'],function () {
    Route::get('register', [BorderRegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [BorderRegisteredUserController::class, 'store']);

    Route::get('login', [BorderAuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [BorderAuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [BorderPasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [BorderPasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [BorderNewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [BorderNewPasswordController::class, 'store'])
                ->name('password.store');
});


Route::group(['middleware'=>['auth:border'],'prefix'=>'border','as'=>'border.'],function() {
        Route::get('/profile', [BorderProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [BorderProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [BorderProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('verify-email', BorderEmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', BorderVerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [BorderEmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [BorderConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [BorderConfirmablePasswordController::class, 'store']);

    Route::put('password', [BorderPasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [BorderAuthenticatedSessionController::class, 'destroy'])
                ->name('logout');


    Route::get('paymentdetails', [BorderProfileController::class, 'paymentdetails'])
                ->name('paymentdetails');
    Route::get('mealdetails', [BorderProfileController::class, 'mealdetails'])
                ->name('mealdetails');
    Route::post('addmeals', [BorderProfileController::class, 'addmeals'])
                ->name('addmeals');
    Route::get('allmonthmeal', [BorderProfileController::class, 'allmonthmeal'])
                ->name('allmonthmeal');

});