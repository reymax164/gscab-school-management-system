<x-layouts.app title="Registar | Reports" header="Reports">
    @php
        $card = 'rounded-xl border 2 border-neutral-900 bg-white p-5 dark:border-neutral-600 dark:bg-neutral-800';
    @endphp

    <div class="space-y-6">
        <form method="GET" class="flex items-center gap-3">
            <label for="school_year" class="text-sm dark:text-neutral-200">School Year:</label>
            <select id="school_year" name="school_year" onchange="this.form.submit()" class="w-44 rounded-md border-neutral-400 bg-white px-3 py-2 text-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
            @foreach ($schoolYear ?? [] as $yearOption)
                <option value="{{ $yearOption }}" @selected($yearOption == ($selectedYear ?? null))>{{ $yearOption }}</option>
            @endforeach
            </select>
        </form>

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            <div class="space-y-6 lg:col-span-2">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    {{-- Total enrollees --}}
                    <section class="{{ $card }} text-center">
                        <h2 class="text-xl font-bold md:text-2xl dark:text-white">Total Students</h2>
                        <p class="mt-4 text-3xl font-bold dark:text-white">{{ number_format($totalStudents ?? 0) }}</p>
                    </section>

                    {{-- Growth --}}
                    <section class="{{ $card }} text-center">
                        <h2 class="text-xl font-bold md:text-2xl dark:text-white">Growth</h2>
                        <p class="mt-4 flex items-center justify-center gap-2 text-2xl dark:text-white">
                            @if(($growth ?? 0) >= 0)
                                <x-heroicon-o-arrow-trending-up class="size-8 text-green-500" />
                            @else
                                <x-heroicon-o-arrow-trending-down class="size-8 text-red-500" />
                            @endif
                            {{ sprintf('%+.2f', $growth ?? 0) }}%
                        </p>
                    </section>
                </div>

                {{-- Enrollment Difference --}}
                <section class="{{ $card }}">
                    <h2 class="text-lg md:text-xl dark:text-white">Enrollment Difference from Previous Year</h2>

                    <ul class="mt-3 space-y-1.5">
                        @forelse($yearlyDifference ?? [] as $row)
                            <li class="flex items-center gap-6 text-lg dark:text-neutral-100">
                                <span class="w-14">{{ $row['year'] }}</span>
                                <span class="w-20 tabular-nums"> {{ ($row['difference'] >= 0 ? '+' : '-' ) . number_format(abs($row['difference'])) }}</span>
                                
                                @if($row['difference'] >= 0)
                                    <x-heroicon-o-arrow-trending-up class="size-5 text-green-500" />
                                @else
                                    <x-heroicon-o-arrow-trending-down class="size-5 text-red-500" />
                                @endif
                            </li>
                        @empty
                            <li class="text-sm italic text-neutral-400">No data available</li>
                        @endforelse
                    </ul>
                </section>
            </div>

            <section class="{{ $card }}">
                <h2 class="text-center text-lg dark:text-white">Enrollees by<br>Grade Level</h2>

                <ul class="mt-4 space-y-1.5">
                    @forelse($gradeLevels ?? [] as $level)
                        <li class="flex items-center justify-between text-base dark:text-neutral-100">
                            <span>{{ $level['name'] }}</span>
                            <span class="tabular-nums">{{ number_format($level['count']) }}</span>
                        </li>
                    @empty
                        <li class="text-center text-sm italic text-neutral-400">No data available</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</x-layouts.app>