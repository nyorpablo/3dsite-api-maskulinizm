<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\UserApiToken;

class UserApiTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user_id' => 1,
                'user_api_key' => 'testkey',
            ]
        ];

        foreach ($users as $value) {
            UserApiToken::create($value);
        }
    }
}
