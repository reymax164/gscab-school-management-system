@props(['title' => ''])

<div {{ $attributes->merge(['class' =>
  'h-48 border-l-8 border-l-blue-900 rounded-md px-4 py-6 col-span-1 bg-white shadow-md hover:shadow-lg overflow-hidden'])
}}>
  @if($title)
    <p class="mb-2 text-blue-900 font-semibold text-lg">{{ $title }}</p>
  @endif
  {{ $slot }}    
</div>