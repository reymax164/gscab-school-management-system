@props(['href'])

<li class="w-full md:w-auto text-center">
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'block w-full py-2 md:py-0 hover:text-yellow-300 hover:bg-blue-950/20 md:hover:bg-transparent rounded-lg transition-colors']) }}>
        {{ $slot }}
    </a>
</li>