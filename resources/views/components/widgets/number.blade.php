@props([
    'header' => 'Widget',
    'icon' => null,
    'href' => '#',
    'footer' => 'View Details',
    'alt' => '',
    'num' => 0,
])

<div {{ $attributes->merge(['class' => 'col-span-1 bg-white rounded-lg h-48 border-l-8 border-l-blue-900 shadow-md hover:shadow-lg transition-shadow py-4 px-6 flex flex-col']) }}>

  {{-- widget header --}}
  <p class="text-blue-900 font-semibold text-lg">{{ $header }}</p>

  <div class="my-auto flex flex-col items-center justify-center">
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }}" class="w-12 h-12">
    @endif

    <p class="text-3xl font-semibold text-gray-800 mt-1">
      {{ $num }}
    </p>
  </div>

  <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-sm font-medium transition-colors">
    {{ $footer }}
  </a>
</div>