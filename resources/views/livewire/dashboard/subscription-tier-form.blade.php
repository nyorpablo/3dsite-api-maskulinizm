<?php

use Livewire\Volt\Component;
use App\Models\SubscriptionTier;
use App\Models\UserApiSubscription;
use App\Models\UserApiToken;

new class extends Component {
    public $tiers;
    public $step = 1;
    public $checkout_data;

    public function mount(){
        $this->tiers = SubscriptionTier::all();
    }

    public function checkout($id){
        $this->checkout_data = SubscriptionTier::where('id', $id)->first();
        $this->step = 2;
    }

    public function buy($data){
        $length = 25;
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = substr(str_shuffle(str_repeat($pool, 60)), 0, $length);
        switch ($data['tier_type']) {
            case 1:
                $usage = '10';
                break;
            case 2:
                $usage = '50';
                break;
            case 3:
                $usage = '100';
                break;
        }
        UserApiSubscription::create([
            'user_id' => auth()->user()->id,
            'subscription_tier' => $data['id'],
        ]);
        UserApiToken::create([
            'api_key' => '3DKEY-'.$key,
            'user_id' => auth()->user()->id,
            'usage' => $usage,
        ]);
        redirect('/subscription');
    }
}

?>

<div class="flex flex-row justify-between">
    @if($step == 1)
        @foreach($tiers as $tier)
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $tier->name }}</h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                {!! $tier->description !!}
            </p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                Billing Cycle : {{ $tier->debit_base }}
            </p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                Price : ${{ $tier->price }}
            </p>
            <button wire:click="checkout({{$tier->id}})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Subscribe
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </button>
        </div>
        @endforeach
    @elseif($step == 2)
    <div class="max-w-sm p-6 bg-white dark:bg-gray-800 dark:border-gray-700">
        <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Your Checkout Data</h2>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
            Tier Name : {{ $checkout_data->name }} <br/>
            Tier Billing Cycle : {{ $checkout_data->debit_base }} Days <br/>
            Tier Price : ${{ $checkout_data->price }} <br/>
        </p>
        <button wire:click="buy({{ $checkout_data }})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Buy now
        </button>
    </div>
    @endif
</div>
