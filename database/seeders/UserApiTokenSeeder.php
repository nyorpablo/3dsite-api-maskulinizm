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
        $tokens = [
            [
                'user_id' => 1,
                'api_key' => '3DKEY-902345',
                'usage' => '100',
                'host_connection' => '["https://maskulinizm.com/3dsite"]',
            ]
        ];

        foreach ($tokens as $value) {
            UserApiToken::create($value);
        }
    }
}
