@props([
    'header' => 'Scrollable Widget',
    'icon' => null,
    'alt' => '',
    'footer' => 'View Details',
    'href' => '#',
    'list' => [],
])

<div {{ $attributes->merge(['class' => 'col-span-1 bg-white rounded-lg h-48 border border-blue-900 shadow-md hover:shadow-lg transition-shadow py-4 px-6 flex flex-col']) }}>
  
  {{-- widget header --}}
  <div class="flex items-center gap-2 mb-2 shrink-0">
    @if($icon)
      <img src="{{ $icon }}" alt="{{ $alt }}" class="w-6 h-6 object-contain">
    @endif
    <p class="text-blue-900 font-semibold text-lg truncate">{{ $header }}</p>
  </div>

  {{-- scrollable list --}}
  <ul class="flex-1 overflow-y-auto my-1 pr-1 divide-y divide-gray-100 text-sm text-gray-800">
    @forelse ($list as $item)
      @php
        $text = is_array($item) ? ($item['text'] ?? '') : (is_object($item) ? $item->text : $item);
        $url   = is_array($item) ? ($item['url'] ?? null) : (is_object($item) ? ($item->url ?? null) : null);
      @endphp

      <li class="py-1.5 px-2 rounded transition-colors text-gray-700 font-medium">
        @if($url)
          <a href="{{ $url }}" class="block hover:text-blue-900 hover:bg-slate-100 -mx-2 -my-1.5 p-1.5 rounded truncate">
            {{ $text }}
          </a>
        @else
          {{-- read only --}}
          <span class="block truncate">
            {{ $text }}
          </span>
        @endif
      </li>
    @empty
      <li class="py-4 text-center text-xs text-gray-400 italic">
        List Empty
      </li>
    @endforelse
  </ul>

  <a href="{{ $href }}" class="ml-auto text-sky-700 hover:text-sky-900 hover:underline text-sm font-medium transition-colors pt-2 shrink-0">
    {{ $footer }}
  </a>

</div>