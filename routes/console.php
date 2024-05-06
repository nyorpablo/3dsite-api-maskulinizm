<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Models\UserApiToken;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $user_data = User::with(['user_api_token','user_api_subscription'])->get();
    foreach($user_data as $user){
        switch ($user->user_api_subscription->subscription_tier) {
            case 1:
                $reseted_usage = 10;
                break;
            case 2:
                $reseted_usage = 50;
                break;
            case 3:
                $reseted_usage = 100;
                break;
        }
        UserApiToken::where('user_id', $user->id)->update([
            'usage' => $reseted_usage
        ]);
    }
})->daily();