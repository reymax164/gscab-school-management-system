<x-layouts.app title="Teacher | Student List" header="Student List"
    class="p-4 md:p-6 flex flex-col">

    <div class="h-full grow px-4 py-6">
        <ul class="flex flex-col gap-4">
            @forelse ($sections as $section)
            <li>
                <div class="bg-white w-full rounded-md border border-gray-400 shadow-sm hover:shadow-md p-4 md:px-6 md:py-4 flex flex-col md:flex-row justify-between items-center text-blue-900 transition-shadow duration-200">

                    <!-- schedule grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 sm:gap-12 w-full md:w-3/4 mb-4 md:mb-0">

                        <!-- grade & S.Y. -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">{{ $section->grade_level === 'Kinder' ? '' : 'Grade ' }}{{ $section->grade_level }}</span>
                            <span class="text-sm mt-1">S.Y. {{ $section->school_year }}</span>
                        </div>

                        <!-- room -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Room</span>
                            <span class="text-sm mt-1">{{ $section->classroom->name ?? 'N/A' }}</span>
                        </div>

                        <!-- adviser -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Adviser</span>
                            <span class="text-sm mt-1">{{ $section->adviser->user->last_name ?? 'N/A' }}{{ isset($section->adviser->user) ? ', '.$section->adviser->user->first_name : '' }}</span>
                        </div>

                        <!-- subject taught by the authenticated teacher in this section -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Subject</span>
                            <span class="text-sm mt-1">
                                @if ($section->subjectSchedules->isNotEmpty())
                                    {{ $section->subjectSchedules->pluck('subject.title')->unique()->implode(', ') }}
                                @elseif ($section->adviser_id === optional(auth()->user()->teacher)->id)
                                    Adviser
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>

                    </div>

                    <!-- action links -->
                    <div class="flex items-center gap-2 w-full md:w-auto justify-end font-medium text-white">
                        <a href="{{ route('teacher.students.show', $section) }}" class="text-xs w-16 text-center hover:underline bg-green-600 rounded-sm px-3 py-2">View</a>
                    </div>

                </div>
            </li>
            @empty
                <!-- empty -->
                <li class="text-center text-gray-500 py-10 bg-gray-50 rounded-md border border-dashed border-gray-300">
                    No classes assigned yet.
                </li>
            @endforelse
        </ul>
    </div>

</x-layouts.app>
