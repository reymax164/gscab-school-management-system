@props([
    'title' => 'Widget',
    'subtitle' => null,
    'footer' => 'View Details',
    'icon' => null,
    'alt' => '',
    'href' => '#',
    'num' => 0,
])

<div {{ $attributes->merge(['class' => 'col-span-1 bg-white rounded-lg h-48 border-l-8 border-l-blue-900 shadow-md hover:shadow-lg transition-shadow p-4 md:px-6 flex flex-col overflow-hidden']) }}>

  {{-- widget title --}}
  <p class="text-blue-900 font-semibold text-md md:text-lg">{{ $title }}</p>
  <p class="text-neutral-800 text-sm">{{ $subtitle }}</p>

  <div class="my-auto flex flex-col items-center justify-center">
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }}" class="w-12 h-12">
    @endif

    <p class="text-lg md:text-2xl font-semibold text-gray-800 mt-1">
      {{ $num }}
    </p>
  </div>

  <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-xs md:text-sm font-medium transition-colors">
    {{ $footer }}
  </a>
</div>