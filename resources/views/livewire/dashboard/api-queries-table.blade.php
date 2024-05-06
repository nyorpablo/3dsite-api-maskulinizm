<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\UserApiQueries;

new class extends Component {
    use WithPagination;

    public $query_data;

    public function mount(){
        $this->query_data = UserApiQueries::where('user_id',auth()->user()->id)->get();
    }
}

?>

<div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Host
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($query_data as $query)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $query->id }}
                    </td>
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $query->host }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
