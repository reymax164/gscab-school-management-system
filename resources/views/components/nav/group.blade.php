@props([
    'label',
    'icon' => null,
    'children' => []
])

@php
    // check if any child route is active
    $isActive = collect($children)->contains(fn($child) => request()->routeIs($child['route'], $child['route'] . '.*'));
@endphp

<li x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="flex flex-col gap-1">
    
    {{-- parent button --}}
    <button 
        @click="open = !open" 
        type="button"
        @class([
            'flex items-center justify-between w-full pl-6 pr-4 py-2.5 transition-colors rounded-l-full font-medium',
            'bg-blue-800/50 text-white' => $isActive, // Subtle highlight if a child is active
            'text-white hover:bg-blue-800/80 dark:hover:bg-slate-700/80' => !$isActive,
        ])
    >
        <div class="flex items-center gap-3">
            @if($icon)
                @svg($icon, 'w-5 h-5 shrink-0')
            @endif
            <span>{{ $label }}</span>
        </div>
        
        {{-- animated chevron --}}
        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0l-4.25-4.25a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- children links --}}
    <ul x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="flex flex-col gap-1 w-full" 
        style="display: none;"
    >
        @foreach($children as $child)
            <x-nav.link 
                :route="$child['route']" 
                :label="$child['label']" 
                :is-child="true" 
            />
        @endforeach
    </ul>
</li>