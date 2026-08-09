@props(['bold' => false])

<td {{ $attributes->merge([
    'class' => 'px-4 py-3 ' . ($bold ? 'font-medium text-neutral-900' : 'text-neutral-600')
]) }}>
    {{ $slot }}
</td>