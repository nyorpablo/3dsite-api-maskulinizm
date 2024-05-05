<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Paul Christian De Guzman',
                'email' => 'paul@test.com',
                'password' => bcrypt('password')
            ]
        ];

        foreach ($users as $value) {
            User::create($value);
        }
    }
}
