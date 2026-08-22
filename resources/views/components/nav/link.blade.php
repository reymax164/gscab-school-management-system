@props([
  'route',
  'label',
  'icon' => null,
  'isChild' => false
])

@php
    // route and child route check
    $isActive = request()->routeIs($route, $route . '.*');
@endphp

<li>
  <a 
    href="{{ route($route) }}" 
    onclick="toggleSidebar(false)"
    @class([
      'flex items-center gap-3 py-2.5 transition-colors rounded-l-full font-medium w-full',
      $isChild ? 'pl-14 text-sm' : 'pl-6', // Increased padding for children
      'text-blue-900 dark:text-slate-800 bg-white font-semibold shadow-sm' => $isActive,
      'text-white hover:bg-blue-800/80 dark:hover:bg-slate-700/80' => !$isActive,
    ])
    @if($isActive) aria-current="page" @endif
  >

    @if($icon)
      @svg($icon, 'w-5 h-5 shrink-0')
    @endif

    <span>{{ $label }}</span>
  </a>
</li>