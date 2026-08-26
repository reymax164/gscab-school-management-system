@props(['name', 'label' => null])

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <input
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['type' => 'text', 'class' => 'bg-white border border-neutral-300 rounded-md px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500']) }}
    />
</div>
