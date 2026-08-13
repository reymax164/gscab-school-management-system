<x-layouts.app title="Student | Grades" header="Grades" class="p-4 md:p-6">

    {{-- S.Y. filter --}}
    <form action="{{ url()->current() }}" method="GET" class="mb-8 max-w-xs">
        <x-form.select name="sy" label="S.Y." onchange="this.form.submit()">
            @for ($i = 2020; $i <= now()->year; $i++)
                @php $sy = "{$i}-" . ($i + 1); @endphp
                <option value="{{ $sy }}" @selected(request('sy') === $sy)>
                    {{ $sy }}
                </option>
            @endfor
        </x-form.select>
    </form>

    {{-- grade level and section --}}
    <h2 class="text-blue-900 dark:text-blue-400 font-semibold text-lg my-6 text-center">
        {{ $level ?? 'Grade #' }}
    </h2>

    {{-- grades --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- get data fron controller --}}
        @forelse ($grades ?? [1, 2, 3] as $grade) 
            <div class="flex flex-col rounded-md bg-white dark:bg-neutral-800 shadow-sm border border-neutral-200 dark:border-neutral-700 hover:shadow-md transition-shadow">
                
                {{-- Csubject and teacher --}}
                <div class="border-b border-neutral-200 dark:border-neutral-700 flex justify-between pt-4 pb-2 px-6">
                    <p class="text-sm text-blue-900 dark:text-blue-400 font-semibold">
                        {{ $grade->subject ?? 'Subject Name' }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                        {{ $grade->teacher ?? 'Teacher Name' }}
                    </p>
                </div>

                {{-- quarters --}}
                <div class="grow px-6 py-4">
                    <div class="flex justify-between text-xs text-neutral-600 dark:text-neutral-400 bg-neutral-100 dark:bg-neutral-700 rounded-md px-4 py-2">
                        <div class="text-center">
                            <p class="font-semibold mb-1">1<sup>st</sup></p>
                            <p>{{ $grade->q1 ?? '-' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold mb-1">2<sup>nd</sup></p>
                            <p>{{ $grade->q2 ?? '-' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold mb-1">3<sup>rd</sup></p>
                            <p>{{ $grade->q3 ?? '-' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold mb-1">4<sup>th</sup></p>
                            <p>{{ $grade->q4 ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- final grade --}}
                <div class="flex justify-end items-center px-6 pt-2 pb-4">
                    <p class="text-blue-900 dark:text-blue-400 font-semibold text-sm mr-4">
                        Final Grade
                    </p>
                    <p class="text-sm font-medium {{ ($grade->final ?? 0) < 75 ? 'text-red-500' : 'text-green-600 dark:text-green-400' }}">
                        {{ $grade->final ?? '0.00' }}
                    </p>
                </div>

            </div>
        @empty
            {{-- empty --}}
            <div class="col-span-full text-center py-12 text-neutral-500 bg-white dark:bg-neutral-800 rounded-md shadow-sm border border-neutral-200 dark:border-neutral-700">
                <p>No grades found for the selected school year.</p>
            </div>
        @endforelse
    </div>

</x-layouts.app>