@props([
    'title' => 'Scrollable Widget',
    'href' => '#',
    'icon' => null,
    'alt' => '',
    'footer' => 'View Details',
    'items' => [],
    'empty'=> 'List Empty'
])

<div {{ $attributes->merge(['class' => 'col-span-1 bg-white rounded-lg h-48 border-l-8 border-l-blue-900 shadow-md hover:shadow-lg transition-shadow p-4 md:px-6 flex flex-col overflow-hidden dark:bg-neutral-800 dark:border-neutral-700 dark:border']) }}>
  
  <!-- title -->
  <div class="flex items-center gap-2 mb-2 shrink-0">
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }}" class="w-6 h-6 object-contain">
    @endif
    <p class="text-blue-900 font-semibold text-md md:text-lg md:truncate dark:text-blue-400">{{ $title }}</p>
  </div>

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
        @if($url)
          {{-- clickable list --}}
          <a href="{{ $url }}" class="flex items-center justify-between gap-3 hover:bg-slate-100 -mx-2 -my-1.5 p-1.5 rounded transition-colors">
            @foreach($cols as $col)
              <span class="truncate {{ $loop->last ? 'text-right text-xs text-gray-500 font-normal' : 'font-medium text-gray-800' }}">
                {{ $col }}
              </span>
            @endforeach
          </a>
        @else
          {{-- read only list --}}
          <div class="flex items-center justify-between gap-3">
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
  <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-xs md:text-sm font-medium transition-colors pt-2 shrink-0 dark:text-neutral-100 dark:hover:text-sky-200">
    {{ $footer }}
  </a>

</div>