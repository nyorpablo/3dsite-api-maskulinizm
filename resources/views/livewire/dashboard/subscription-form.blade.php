<?php

use App\Models\User;
use App\Models\UserApiToken;
use Livewire\Volt\Component;
use Carbon\Carbon;
use Illuminate\Support\Arr;

new class extends Component {
    public $user_data;
    public $addHostModal = false;
    public $addHostModalApiKeyData;
    public $disabledButton = 'bg-blue-700';
    public $disabledAriaButton;
    public $hostname;
    
    public function mount()
    {
        $data = User::where('id', auth()->user()->id)
                                        ->with(['user_api_subscription.user_tier'])
                                        ->with('user_api_token')
                                        ->first();
        if(!isset($data->user_api_subscription)){
            $this->user_data = '';
        }else{
            $this->user_data = [
                'tier_name' => $data->user_api_subscription->user_tier->name,
                'tier_price' => $data->user_api_subscription->user_tier->price,
                'billing_cycle' => $data->user_api_subscription->user_tier->debit_base.' Days',
                'user_api_id' => $data->user_api_token->id,
                'api_token' => $data->user_api_token->api_key,
                'api_usage' => $data->user_api_token->usage,
                'host_connection' => json_decode($data->user_api_token->host_connection),
            ];

            switch ($data->user_api_subscription->subscription_tier) {
                case 1:
                    $host_available = 1;
                    break;
                case 2:
                    $host_available = 5;
                    break;
                case 3:
                    $host_available = 10;
                    break;
            }

            $count_host = json_decode($data->user_api_token->host_connection);
            if($count_host != NULL){
                if(count($count_host) == $host_available){
                    $this->disabledAriaButton = 'disabled';
                    $this->disabledButton = 'bg-gray-700';
                }else{
                    $this->disabledAriaButton = '';
                }
            }else{
                $this->disabledAriaButton = '';
            }
        }
    }

    public function showViewModal($id){
        $this->addHostModal = true;
    }

    public function addHost($id){
        $get_token_host = UserApiToken::where('id', $id)->first();
        $array_host = json_decode($get_token_host->host_connection);
        if($array_host == NULL){
            $array_host = (array) $array_host;
        }
        array_push($array_host,$this->hostname);
        UserApiToken::where('id',$id)->update([
            'host_connection' => $array_host
        ]);
        redirect('/subscription');
    }

    public function deletHost($id, $name){
        $get_token_host = UserApiToken::where('id', $id)->first();
        $array_host = json_decode($get_token_host->host_connection);
        $search_array = array_search($name,$array_host);
        $unset_host = Arr::except($array_host, [$search_array]);
        $new_array = [];
        foreach($unset_host as $value){
            $new_array[] =  $value;
        }
        UserApiToken::where('id',$id)->update([
            'host_connection' => json_encode($new_array)
        ]);
        redirect('/subscription');
    }
}

?>

<div>
    @if(!empty($user_data))
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 ">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Subscription Information') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Here is your subscription information.") }}
                    </p>
                </header>
                <div class="mt-6 ">
                    <label for="user_subscription_tier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subscription Name</label>
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="w-4 h-4 text-gray-500 dark:text-gray-400 fa fa-space-shuttle"></i>
                        </div>
                        <input 
                            type="text" 
                            id="user_subscription_tier" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Subscription Name"
                            value="{{ $user_data['tier_name'] }}"
                            disabled>
                    </div>
                    <label for="user_subscription_tier_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="w-4 h-4 text-gray-500 dark:text-gray-400 fa fa-dollar-sign"></i>
                        </div>
                        <input 
                            type="text" 
                            id="user_subscription_tier_price" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Price"
                            value="{{ $user_data['tier_price'] }}"
                            disabled>
                    </div>
                    <label for="subscription_cycle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Next Billing Cycle</label>
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="w-4 h-4 text-gray-500 dark:text-gray-400 fa fa-calendar"></i>
                        </div>
                        <input 
                            type="text" 
                            id="subscription_cycle" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Price"
                            value="{{ $user_data['billing_cycle'] }}"
                            disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 ">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('API token Information') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Here is your API token information.") }}
                    </p>
                </header>
                <div class="mt-6 ">
                    <label for="token" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Token</label>
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="w-4 h-4 text-gray-500 dark:text-gray-400 fa fa-code"></i>
                        </div>
                        <input 
                            type="text" 
                            id="token" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Subscription Name"
                            value="{{ $user_data['api_token'] }}"
                            disabled>
                    </div>
                    <label for="token_usage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Token Usage</label>
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="w-4 h-4 text-gray-500 dark:text-gray-400 fa fa-stopwatch"></i>
                        </div>
                        <input 
                            type="text" 
                            id="token_usage" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Price"
                            value="{{ $user_data['api_usage'] }}"
                            disabled>
                    </div>
                    <div class="flex flex-row justify-between mb-2">
                        <label for="host_connection" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Host Connections</label>
                        <button {{ $disabledAriaButton }} wire:click="showViewModal({{ $user_data['user_api_id'] }})" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white {{ $disabledButton }} rounded-lg hover:{{ $disabledButton }} focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Add Host
                        </button>
                    </div>
                    <div class="relative mb-6">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Host
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($user_data['host_connection'] != '')
                                        @foreach($user_data['host_connection'] as $value)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $value }}
                                            </th>
                                            <td class="px-6 py-4">
                                                <button wire:click="deletHost({{ $user_data['user_api_id'] }}, '{{ $value }}')" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 ">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Unsubscribe') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("You can unsubscribe here.") }}
                    </p>
                </header>
                <div class="mt-6 ">
                    <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Unsubscribe</button>
                </div>
            </div>
        </div>
    </div>
    <x-modal name="addHostModal" blur="sm" wire:model="addHostModal">
        <x-card title="Add Host Modal">
            <form class="max-w-md mx-auto">
                <div class="relative z-0 w-full mb-5 group">
                    <input type="url" wire:model="hostname" name="floating_host" id="floating_host" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="floating_host" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Host Name</label>
                </div>
                <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" wire:click="addHost({{ $user_data['user_api_id'] }})">Add</button>
            </form>
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
            </x-slot>
        </x-card>
    </x-modal>
    @else
    <p class="bg-red-400 p-3 text-white rounded">
        No Subscription Yet. <a href="{{ url('subscription-tier') }}" class="text-blue-700">Subscibe now!</a>
    </p>
    @endif
</div>
