@props([
    'title' => '',
    'header' => false,
])

<div {{ $attributes->merge(['class' =>
  'flex flex-col
  bg-white dark:bg-neutral-800
  h-48 rounded-md
  overflow-hidden col-span-1  
  border border-neutral-400 dark:border-neutral-700
  shadow-sm hover:shadow-md'])
}}>
  @if($title)
    <div @class([
      'flex items-center shrink-0 mb-2',
      'bg-blue-900 text-white justify-center font-semibold p-2' => $header,
      'pt-4 px-6' => !$header,
    ])>
      <p @class([
        'text-md md:text-lg md:truncate',
        'text-blue-900 font-semibold dark:text-blue-400' => !$header,
      ])>
        {{ $title }}
      </p>
    </div>
  @endif

  <div class="flex-1 overflow-y-auto px-6 pb-4">
    {{ $slot }}    
  </div>
</div>