@props(['headers' => []])

<div class="mt-6 flex-1 bg-white border border-neutral-300 rounded-md shadow-sm overflow-x-auto dark:bg-neutral-800 dark:border-neutral-700 dark:border">
    <table class="w-full text-left border-collapse min-w-150">
        <thead>
            <tr class="bg-blue-900 text-neutral-50 text-sm">
                @foreach($headers as $index => $header)
                    <th scope="col" class="px-4 py-3 font-medium {{ $loop->first ? 'rounded-tl-md' : '' }} {{ $loop->last ? 'rounded-tr-md' : '' }}">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-neutral-200">
            {{ $slot }}
        </tbody>
    </table>
</div>