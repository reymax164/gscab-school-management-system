@props([
    'title' => 'Number Widget',
    'subtitle' => null,
    
    'header' => false,
    'footer' => 'View Details',

    'icon' => null,
    'alt' => '',
    
    'num' => 0,
    'href' => '#',
])

<div {{ $attributes->merge(['class' =>
  'bg-white dark:bg-neutral-800
  flex flex-col overflow-hidden col-span-1
  h-48 rounded-lg
  border border-neutral-400
  shadow-sm hover:shadow-md transition-shadow
  dark:border dark:border-neutral-700'
])}}>

  {{-- widget title --}}
  @if($title)
    <div @class([
      'flex flex-col shrink-0',
      'bg-blue-900 text-white items-center justify-center text-center p-2' => $header,
      'pt-4 px-4 md:px-6' => !$header,
    ])>
      <p @class([
        'font-semibold text-md md:text-lg md:truncate',
        'text-blue-900 dark:text-blue-400' => !$header,
      ])>
        {{ $title }}
      </p>
      
      @if($subtitle)
        <p @class([
          'text-sm',
          'text-blue-100' => $header,
          'text-neutral-800 dark:text-blue-200' => !$header,
        ])>
          {{ $subtitle }}
        </p>
      @endif
    </div>
  @endif

  {{-- Content Wrapper --}}
  <div class="flex flex-col flex-1 px-4 md:px-6 pb-4">
    <div class="my-auto flex flex-col items-center justify-center">
      @if($icon)
        <img src="{{ $icon }}" alt="{{ $alt }}" class="w-12 h-12">
      @endif

      <p class="text-lg md:text-2xl font-semibold text-gray-800 mt-1 dark:text-neutral-100">
        {{ $num }}
      </p>
    </div>

    <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-xs md:text-sm font-medium transition-colors shrink-0 dark:text-neutral-100 dark:hover:text-gray-50/50">
      {{ $footer }}
    </a>
  </div>
  
</div>