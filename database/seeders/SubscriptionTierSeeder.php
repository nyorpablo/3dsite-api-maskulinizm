<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SubscriptionTier;

class SubscriptionTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tier = [
            [
                'name' => 'Starter Tier',
                'description' => '<h4><b>Tier Inclusion</b></h4>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure dolore fugit est id deserunt sequi ipsam atque odio, odit rerum, pariatur consequatur facere vero accusamus ipsa, explicabo cum earum iusto.
                                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg dark:border-gray-600">API Calls Per Day : 10</li>
                                    <li class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600">Connections : 1</li>
                                </ul>',
                'tier_type' => '1',
                'price' => '3',
                'debit_base' => '30',
            ],
            [
                'name' => 'Hobbyist Tier',
                'description' => '<h4><b>Tier Inclusion</b></h4>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure dolore fugit est id deserunt sequi ipsam atque odio, odit rerum, pariatur consequatur facere vero accusamus ipsa, explicabo cum earum iusto.
                                    <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg dark:border-gray-600">API Calls Per Day : 50</li>
                                        <li class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600">Connections : 5</li>
                                    </ul>',
                'tier_type' => '2',
                'price' => '8',
                'debit_base' => '30',
            ],
            [
                'name' => 'Business Tier',
                'description' => '<h4><b>Tier Inclusion</b></h4>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure dolore fugit est id deserunt sequi ipsam atque odio, odit rerum, pariatur consequatur facere vero accusamus ipsa, explicabo cum earum iusto.
                                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg dark:border-gray-600">API Calls Per Day : 100</li>
                                    <li class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600">Connections : 10</li>
                                </ul>',
                'tier_type' => '3',
                'price' => '12',
                'debit_base' => '30',
            ],
        ];

        foreach ($tier as $value) {
            SubscriptionTier::create($value);
        }
    }
}
