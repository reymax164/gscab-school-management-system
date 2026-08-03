@props([
  'route',
  'label',
  'src' => null,
  'alt' => null
])

@php
    // route check
    $isActive = request()->routeIs($route);
@endphp

<li @class([
  'pl-6 py-2 transition-colors',
  'text-blue-900 font-semibold bg-white rounded-l-full' => $isActive,
  'text-white hover:bg-blue-800 rounded-l-full' => !$isActive,
])>
  <a href="{{ route($route) }}" class="flex items-center gap-2">
      <img src="{{ $src }}" alt="{{ $alt }}">
      <span>{{$label}}</span>
  </a>
</li>