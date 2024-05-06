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

Route::view('viewer/{stl_name}', 'stl/viewer')
    ->name('viewer');

Route::view('subscription', 'dashboard/subscription')
    ->middleware(['auth', 'verified'])
    ->name('subscription');

Route::view('queries', 'dashboard/api-queries')
    ->middleware(['auth', 'verified'])
    ->name('queries');

Route::view('documentation', 'documentation')
    ->name('documentation');
    
Route::view('subscription-tier', 'dashboard/subscription-tier')
    ->middleware(['auth', 'verified', SubscriptionPage::class])
    ->name('subscription-tier');

require __DIR__.'/auth.php';
