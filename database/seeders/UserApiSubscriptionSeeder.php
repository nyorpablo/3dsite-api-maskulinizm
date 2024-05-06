<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\UserApiSubscription;

class UserApiSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_sub = [
            [
                'user_id' => 1,
                'subscription_tier' => 3,
            ]
        ];

        foreach ($user_sub as $value) {
            UserApiSubscription::create($value);
        }
    }
}
