<x-layouts.app title="Teacher | Grades" header="Student Grades"
    class="p-4 md:p-6 flex flex-col">

    <div class="h-full grow px-4 py-6">
        <ul class="flex flex-col gap-4">
            @forelse ($subjectSchedules as $subjectSchedule)
            <li>
                <div class="bg-white w-full rounded-md border border-gray-400 shadow-sm hover:shadow-md p-4 md:px-6 md:py-4 flex flex-col md:flex-row justify-between items-center text-blue-900 transition-shadow duration-200">

                    <!-- schedule grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-12 w-full md:w-3/4 mb-4 md:mb-0">

                        <!-- grade & S.Y. -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">{{ $subjectSchedule->section->grade_level === 'Kinder' ? '' : 'Grade ' }}{{ $subjectSchedule->section->grade_level }}</span>
                            <span class="text-sm mt-1">S.Y. {{ $subjectSchedule->section->school_year }}</span>
                        </div>

                        <!-- room -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Room</span>
                            <span class="text-sm mt-1">{{ $subjectSchedule->section->classroom->name ?? 'N/A' }}</span>
                        </div>

                        <!-- subject -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Subject</span>
                            <span class="text-sm mt-1">{{ $subjectSchedule->subject->title ?? 'N/A' }}</span>
                        </div>

                    </div>

                    <!-- action links -->
                    <div class="flex items-center gap-2 w-full md:w-auto justify-end font-medium text-white">
                        <a href="{{ route('teacher.grades.show', $subjectSchedule) }}" class="text-xs w-16 text-center hover:underline bg-green-600 rounded-sm px-3 py-2">View</a>
                    </div>

                </div>
            </li>
            @empty
                <!-- empty -->
                <li class="text-center text-gray-500 py-10 bg-gray-50 rounded-md border border-dashed border-gray-300">
                    No subjects assigned yet.
                </li>
            @endforelse
        </ul>
    </div>

</x-layouts.app>