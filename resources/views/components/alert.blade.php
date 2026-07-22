@props(['type' => 'success'])

@php
    $colors = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-300',
            'text' => 'text-green-800',
            'hover' => 'hover:text-green-900',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-300',
            'text' => 'text-red-800',
            'hover' => 'hover:text-red-900',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-300',
            'text' => 'text-yellow-800',
            'hover' => 'hover:text-yellow-900',
        ],
    ][$type];
    
    $duration = $type === 'success' ? 3000 : 5000;
@endphp

<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => { show = false }, {{ $duration }})" 
    x-show="show" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="relative p-4 pr-10 mt-4 mb-4 text-sm {{ $colors['text'] }} border {{ $colors['border'] }} rounded-lg {{ $colors['bg'] }}"
    role="alert"
    {{ $attributes }}
>
    {{ $slot }}
    
    <button 
        @click="show = false" 
        type="button" 
        class="absolute top-1/2 right-3 transform -translate-y-1/2 text-xl {{ $colors['text'] }} {{ $colors['hover'] }} focus:outline-none"
        aria-label="Close"
    >
        &times;
    </button>
</div>