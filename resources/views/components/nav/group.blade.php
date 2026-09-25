@props([
    'label',
    'icon' => null,
    'children' => []
])

@php
    $hasActiveChild = false;

    foreach ($children as $child) {
        $childRoute = $child['route'] ?? '';
        
        // trim '.index' to get the base prefix, matching x-nav.link wildcard logic
        $baseRoute = str_ends_with($childRoute, '.index') ? substr($childRoute, 0, -6) : $childRoute;
        
        // flag active if current route matches child or any sub-route (e.g. .show, .edit)
        if (request()->routeIs($childRoute, $baseRoute . '.*')) {
            $hasActiveChild = true;
            break;
        }
    }
@endphp

<li x-data="{ open: {{ $hasActiveChild ? 'true' : 'false' }} }" class="flex flex-col gap-1">
    
    {{-- parent button --}}
    <button 
        @click="open = !open" 
        type="button"
        @class([
            'flex items-center justify-between w-full pl-6 pr-4 py-2.5 transition-colors rounded-l-full font-medium',
            'bg-blue-800/50 text-white' => $hasActiveChild,
            'text-white hover:bg-blue-800/80 dark:hover:bg-slate-700/80' => !$hasActiveChild,
        ])
    >
        <div class="flex items-center gap-3">
            @if($icon)
                @svg($icon, 'w-5 h-5 shrink-0')
            @endif
            <span>{{ $label }}</span>
        </div>
        
        {{-- animated chevron using Heroicon --}}
        <span :class="{'rotate-180': open}" class="transition-transform duration-200 inline-flex items-center">
            <x-heroicon-m-chevron-down class="w-4 h-4" />
        </span>
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