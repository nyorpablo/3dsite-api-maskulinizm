<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SubscriptionPage;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('subscription', 'dashboard/subscription')
    ->middleware(['auth', 'verified'])
    ->name('subscription');
    
Route::view('subscription-tier', 'dashboard/subscription-tier')
    ->middleware(['auth', 'verified', SubscriptionPage::class])
    ->name('subscription-tier');

require __DIR__.'/auth.php';
