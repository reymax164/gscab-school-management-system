@props(['route', 'label'])

@php
    $isActive = request()->routeIs($route);
@endphp

<li class="w-full md:w-auto text-center">
  <a href="{{ route($route) }}" {{ $attributes->class([
      //base
      'block w-full py-2 md:py-0 rounded-lg transition-colors',
      
      // hover
      'hover:text-gray-300 hover:bg-blue-950/20 md:hover:bg-transparent',
      
      // active
      'text-yellow-300 bg-blue-950/20 md:bg-transparent' => $isActive,
  ]) }}>
      {{ $label }}
  </a>
</li>