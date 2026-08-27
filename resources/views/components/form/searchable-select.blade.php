@props([
    'options',
    'idField',
    'queryField',
    'openField',
    'posField',
    'inputName',
    'placeholder' => 'Search...',
])

{{-- teleported to <body> so the panel never gets clipped by the table's scroll/overflow containers --}}
<div class="relative" @click.away="slot.{{ $openField }} = false">
    <input type="text" x-model="slot.{{ $queryField }}" autocomplete="off" placeholder="{{ $placeholder }}"
        @focus="slot.{{ $openField }} = true; positionDropdown($el, slot, '{{ $posField }}')"
        class="w-full bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
    <input type="hidden" :name="{{ $inputName }}" :value="slot.{{ $idField }}">

    <template x-teleport="body">
        <div x-show="slot.{{ $openField }}" x-cloak
            :style="`top:${slot.{{ $posField }}.top}px; left:${slot.{{ $posField }}.left}px; width:${slot.{{ $posField }}.width}px;`"
            class="fixed z-50 max-h-48 overflow-auto bg-white border border-gray-300 rounded-md shadow-lg">
            <template x-for="option in filteredOptions({{ $options }}, slot.{{ $queryField }})" :key="option.id">
                <div @click="slot.{{ $idField }} = option.id; slot.{{ $queryField }} = option.label; slot.{{ $openField }} = false"
                    class="px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer" x-text="option.label"></div>
            </template>
            <div x-show="filteredOptions({{ $options }}, slot.{{ $queryField }}).length === 0" class="px-3 py-2 text-sm text-gray-400">No results</div>
        </div>
    </template>
</div>
