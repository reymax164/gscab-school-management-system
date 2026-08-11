@props([
    'title' => 'Widget',
    'subtitle' => null,
    'footer' => 'View Details',
    'icon' => null,
    'alt' => '',
    'href' => '#',
    'num' => 0,
])

<div {{ $attributes->merge(['class' => 'col-span-1 bg-white rounded-lg h-48 border-l-8 border-l-blue-900 shadow-md hover:shadow-lg transition-shadow p-4 md:px-6 flex flex-col overflow-hidden dark:bg-neutral-800 dark:border-neutral-700 dark:border']) }}>

  {{-- widget title --}}
  <p class="text-blue-900 font-semibold text-md md:text-lg dark:text-blue-400">{{ $title }}</p>
  <p class="text-neutral-800 text-sm dark:text-blue-400">{{ $subtitle }}</p>

  <div class="my-auto flex flex-col items-center justify-center">
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }}" class="w-12 h-12">
    @endif

    <p class="text-lg md:text-2xl font-semibold text-gray-800 mt-1 dark:text-neutral-100">
      {{ $num }}
    </p>
  </div>

  <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-xs md:text-sm font-medium transition-colors  dark:text-neutral-100 dark:hover:text-gray-50/50">
    {{ $footer }}
  </a>
</div>