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
        $users = [
            [
                'user_id' => 1,
                'subscription_type' => 3,
                'usage' => 100,
            ]
        ];

        foreach ($users as $value) {
            UserApiSubscription::create($value);
        }
    }
}
