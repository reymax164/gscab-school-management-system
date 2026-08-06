@props([
    'title' => 'Widget',
    'href' => '#',
    'icon' => null,
    'alt' => '',
])

<div {{ $attributes->merge(['class' => 'col-span-1 bg-white rounded-lg h-48 border-l-8 border-l-blue-900 shadow-md hover:shadow-lg transition-shadow py-4 px-6 flex flex-col']) }}>
  <p class="text-blue-900 font-semibold text-lg">{{ $title }}</p>

  <div class="my-auto flex flex-col items-center justify-center">
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }} icon" class="w-12 h-12">
    @endif

    <p class="text-3xl font-bold text-gray-800 mt-1">
      {{ $slot }}
    </p>
  </div>

  <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-sm font-medium transition-colors">
    View Details
    {{-- &rarr; --}}
  </a>
</div>