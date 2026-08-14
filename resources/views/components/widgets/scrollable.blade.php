@props([
    'title' => 'Scrollable Widget',
    'header' => false,
    
    'icon' => null,
    'alt' => '',

    'footer' => 'View Details',
    'href' => '#',

    'items' => [],
    'empty'=> 'List Empty'
])

<div {{ $attributes->merge(['class' =>
  'flex flex-col col-span-1 overflow-hidden
  bg-white dark:bg-neutral-800
  h-48 rounded-lg
  border border-neutral-400
  shadow-md hover:shadow-lg transition-shadow
  dark:border-neutral-700 dark:border'
])}}>
  
  <!-- title -->
  <div @class([
    'flex items-center gap-2 mb-2 shrink-0',
    'bg-blue-900 text-white justify-center font-semibold p-2' => $header,
    'pt-4 px-4 md:px-6' => !$header,
  ])>
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }}" class="w-6 h-6 object-contain">
    @endif
    
    <p @class([
        'text-md md:text-lg md:truncate',
        'text-blue-900 font-semibold dark:text-blue-400' => !$header,
    ])>
        {{ $title }}
    </p>
  </div>

  <!-- wrapper -->
  <div class="flex flex-col flex-1 overflow-hidden px-4 md:px-6 pb-4">
    <ul class="flex-1 overflow-y-auto my-1 pr-1 divide-y divide-gray-100 text-sm text-gray-800">
      @forelse ($items as $item)
        @php
          // get url
          $url = is_array($item) ?
            ($item['url'] ?? null) : (is_object($item) ?
            ($item->url ?? null) : null);

          // get columns
          if (is_array($item) && isset($item['cols'])) {
              $cols = $item['cols'];
          } elseif (is_array($item)) {
              $cols = array_filter($item, fn($k) => $k !== 'url', ARRAY_FILTER_USE_KEY);
          } else {
              $cols = [$item];
          }
        @endphp

        <li class="py-1.5 px-2 rounded transition-colors">
          @php
              // col count
              $colCount = count($cols);
          @endphp

          @if($url)
            {{-- clickable list --}}
            <a href="{{ $url }}" class="grid items-center gap-3 hover:bg-slate-100 -mx-2 -my-1.5 p-1.5 rounded transition-colors" style="grid-template-columns: repeat({{ $colCount }}, minmax(0, 1fr));">
              @foreach($cols as $col)
                <span class="truncate {{ $loop->last ? 'text-right text-xs text-gray-500 font-normal' : 'font-medium text-gray-800' }}">
                  {{ $col }}
                </span>
              @endforeach
            </a>
          @else
            {{-- read only list --}}
            <div class="grid items-center gap-3" style="grid-template-columns: repeat({{ $colCount }}, minmax(0, 1fr));">
              @foreach($cols as $col)
                <span class="truncate {{ $loop->last ? 'text-right text-xs text-gray-500 font-normal' : 'font-medium text-gray-800' }}">
                  {{ $col }}
                </span>
              @endforeach
            </div>
          @endif
        </li>
      @empty
        <li class="py-4 text-center text-xs text-gray-400 italic">
          {{ $empty }}
        </li>
      @endforelse
    </ul>

    {{-- details link --}}
    <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-xs md:text-sm font-medium transition-colors pt-2 shrink-0 dark:text-neutral-100 dark:hover:text-gray-50/50">
      {{ $footer }}
    </a>
  </div>

</div>