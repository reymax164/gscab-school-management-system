@props([
    'title' => 'Table Widget',
    'header' => true,
    
    'icon' => null,
    'alt' => '',

    'footer' => 'View Details',
    'href' => '#',

    'columns' => [],
    'rows' => [],
    'empty'=> 'No records found'
])

<div {{ $attributes->merge(['class' => 
  'flex flex-col col-span-1 overflow-hidden 
  bg-white dark:bg-neutral-800 
  h-72 rounded-lg 
  border border-neutral-400 
  shadow-md hover:shadow-md transition-shadow 
  dark:border-neutral-700 dark:border'
])}}>
  
  <!-- title -->
  <div @class([
    'flex items-center gap-2 shrink-0',
    'bg-blue-900 text-white justify-center font-semibold p-2' => $header,
    'pt-4 px-4 md:px-6 pb-2' => !$header,
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

  <!-- multi column list -->
  <div class="flex-1 overflow-y-auto relative">
    <table class="w-full text-sm text-left">
      
      <!-- column headers-->
      @if(!empty($columns))
      <thead class="sticky top-0 z-10
           bg-white dark:bg-neutral-800
             shadow-[0_1px_0_0_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.1)]
             text-blue-900 dark:text-blue-500">
        <tr>
          @foreach($columns ?? [] as $col)
            <th scope="col" class="px-4 py-2.5 font-semibold whitespace-nowrap {{ $loop->last ? 'text-right' : '' }}">
              {{ $col }}
            </th>
          @endforeach
        </tr>
      </thead>
      @endif

      <!-- body -->
      <tbody class="divide-y divide-gray-100 dark:divide-neutral-700 text-gray-800 dark:text-gray-200">
        @forelse ($rows ?? [] as $row)
          @php
            // get url
            $url = is_array($row) ? ($row['url'] ?? null) : (is_object($row) ? ($row->url ?? null) : null);

            // get columns
            if (is_array($row) && isset($row['cols'])) {
                $cols = $row['cols'];
            } elseif (is_array($row)) {
                $cols = array_filter($row, fn($k) => $k !== 'url', ARRAY_FILTER_USE_KEY);
            } else {
                $cols = (array) $row;
            }
            $cols = array_values($cols);
          @endphp

          {{-- clickable --}}
          <tr class="hover:bg-slate-50 dark:hover:bg-neutral-700/50 transition-colors {{ $url ? 'cursor-pointer' : '' }}"
              @if($url) onclick="window.location='{{ $url }}'" @endif>
            
            @foreach($cols as $col)
              <td class="px-4 py-2 whitespace-nowrap {{ $loop->last ? 'text-right text-xs text-gray-500 font-normal' : 'font-medium text-gray-800 dark:text-gray-200' }}">
                {{ $col }}
              </td>
            @endforeach

          </tr>
        @empty
          <tr>
            <td colspan="{{ count($columns) ?: 1 }}" class="py-8 text-center text-xs text-gray-400 italic">
              {{ $empty }}
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- footer -->
  <div class="px-4 md:px-6 py-2 border-t border-gray-100 dark:border-neutral-700 shrink-0 bg-white dark:bg-neutral-800 flex justify-end">
    <a href="{{ $href }}" class="text-sky-700 hover:text-sky-900 hover:underline text-xs md:text-sm font-medium transition-colors dark:text-neutral-100 dark:hover:text-gray-50/50">
      {{ $footer }}
    </a>
  </div>

</div>