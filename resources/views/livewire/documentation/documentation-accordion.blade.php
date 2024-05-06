<?php

use Livewire\Volt\Component;

new class extends Component {
    public $accordion = [
        '1' => 'hidden',
        '2' => 'hidden',
        '3' => 'hidden',
        '4' => 'hidden',
    ];
    
    public function changeAccordionStatus($accordion, $count){
        if($accordion == 'hidden'){
            $this->accordion[$count] = '';
        }else{
            $this->accordion[$count] = 'hidden';
        }
    }
}

?>

<div>
    <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
        <h2 id="accordion-flush-heading-1">
            <button wire:click="changeAccordionStatus('{{ $accordion[1] }}', 1)" type="button" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3" data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
                <span>Getting Started</span>
                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
            </button>
        </h2>
        <div id="accordion-flush-body-1" class="{{ $accordion[1] }}" aria-labelledby="accordion-flush-heading-1">
            <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                <p class="mb-2 text-gray-500 dark:text-gray-400">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi laboriosam quos inventore officiis cupiditate totam dolor quo itaque. Expedita consequatur nihil exercitationem provident modi perspiciatis distinctio tempore nam eligendi quibusdam.
                </p>
            </div>
        </div>
        <h2 id="accordion-flush-heading-2">
            <button wire:click="changeAccordionStatus('{{ $accordion[2] }}', 2)" type="button" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
                <span>Installation</span>
                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
            </button>
        </h2>
        <div id="accordion-flush-body-2" class="{{ $accordion[2] }}" aria-labelledby="accordion-flush-heading-2">
            <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                <p class="mb-2 text-gray-500 dark:text-gray-400">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Commodi illum possimus ad laborum autem expedita consectetur officiis non quis unde magnam minima dolorum perferendis esse, repellat dolorem harum voluptatibus laudantium?
                </p>
            </div>
        </div>
        <h2 id="accordion-flush-heading-3">
            <button wire:click="changeAccordionStatus('{{ $accordion[3] }}', 3)" type="button" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
                <span>API Query</span>
                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
            </button>
        </h2>
        <div id="accordion-flush-body-3" class="{{ $accordion[3] }}" aria-labelledby="accordion-flush-heading-3">
            <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                <p class="mb-2 text-gray-500 dark:text-gray-400">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Perferendis, pariatur porro. Temporibus sapiente inventore eligendi commodi iure alias saepe nihil blanditiis dolorem maxime. Unde deserunt minus in quis optio ratione?
                </p>
            </div>
        </div>
        <h2 id="accordion-flush-heading-4">
            <button wire:click="changeAccordionStatus('{{ $accordion[4] }}', 4)" type="button" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 gap-3" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
                <span>About The Wordpress Plugin</span>
                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
            </button>
        </h2>
        <div id="accordion-flush-body-3" class="{{ $accordion[4] }}" aria-labelledby="accordion-flush-heading-4">
            <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                <p class="mb-2 text-gray-500 dark:text-gray-400">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam dolorem nihil vitae atque quaerat praesentium blanditiis, magni, ea et totam vel placeat. Molestiae nesciunt consectetur voluptas enim ducimus voluptate quod?
                </p>
            </div>
        </div>
    </div>
</div>
