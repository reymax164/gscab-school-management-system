@props(['name', 'label' => null])

<div class="flex items-center gap-2">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-neutral-700 dark:text-neutral-100">
            {{ $label }}
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $name }}" 
        {{ $attributes->merge(['class' => 'bg-white w-32 border border-neutral-300 rounded-md px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500']) }}
    >
        {{ $slot }}
    </select>
</div>