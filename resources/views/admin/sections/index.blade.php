<x-layouts.app title="Admin | Schedules" header="Manage Schedules"
    class="p-4 md:p-6 flex flex-col">

    <div class="w-full flex justify-between flex-row items-center">
        <form action="{{ route('admin.sections.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 mb-6 max-w-md grow">
            <x-form.select name="sort" id="" label="Sort by" class="w-52">
                <option value="">Grade Level (Acending)</option>
                <option value="">Grade Level (Descending)</option>
                <option value="">Last Added/Edited</option>
                <option value="">Room Number</option>
                <option value="">Class Adviser</option>
            </x-form.select>

            {{-- school year filter --}}
            <x-form.select label="S.Y." name="sy" onchange="this.form.submit()">
                <option value="">All School Years</option>
                @foreach ($schoolYears as $schoolYear)
                    <option value="{{ $schoolYear }}" {{ request('sy') == $schoolYear ? 'selected' : '' }}>{{ $schoolYear }}</option>
                @endforeach
            </x-form.select>
        </form>

        <div>
            <a href="{{ route('admin.sections.create') }}" class="bg-blue-900 hover:bg-blue-900/90 text-white text-sm px-3 py-2 rounded-sm inline-flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Schedule
            </a>
        </div>
    </div>

    {{-- missing schedules Warning --}}
    @if(!empty($missingGrades) && $targetSy)
        <div class="mx-4 mt-2 rounded-md border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-yellow-800">
                        Missing Schedules for S.Y. {{ $targetSy }}
                    </h3>

                    <div class="mt-2 text-sm text-yellow-700">
                        <p>The following grade levels do not have a schedule created yet. It is recommended to create at least one schedule per grade level:</p>
                        <ul class="mt-2 flex flex-wrap gap-2">
                            @foreach($missingGrades as $grade)
                            <li class="bg-yellow-200/50 px-2 py-1 rounded border border-yellow-300 font-medium text-xs">
                                {{ $grade === 'Kinder' ? 'Kindergarten' : 'Grade ' . $grade }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="h-full grow px-4 py-6">
        <ul class="flex flex-col gap-4">
            @forelse ($schedules ?? [] as $schedule)
            <li>
                <div class="bg-white w-full rounded-md border border-gray-400 shadow-sm hover:shadow-md p-4 md:px-6 md:py-4 flex flex-col md:flex-row justify-between items-center text-blue-900 transition-shadow duration-200">
                
                    <!-- schedule grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-12 w-full md:w-3/4 mb-4 md:mb-0">

                        <!-- grade & S.Y. -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">{{ $schedule->grade_level === 'Kinder' ? '' : 'Grade ' }}{{ $schedule->grade_level }}</span>
                            <span class="text-sm mt-1">S.Y. {{ $schedule->school_year }}</span>
                        </div>

                        <!-- room -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Room</span>
                            <span class="text-sm mt-1">{{ $schedule->classroom->name ?? 'N/A' }}</span>
                        </div>

                        <!-- adviser -->
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">Adviser</span>
                            <span class="text-sm mt-1">{{ $schedule->adviser->user->last_name ?? 'N/A' }}{{ isset($schedule->adviser->user) ? ', '.$schedule->adviser->user->first_name : '' }}</span>
                        </div>

                    </div>

                    <!-- action links -->
                    <div class="flex items-center gap-2 w-full md:w-auto justify-end font-medium text-white">
                        <a href="{{ route('admin.sections.show', $schedule) }}" class="text-xs w-16 text-center hover:underline bg-green-600 rounded-sm px-3 py-2">View</a>
                        <a href="{{ route('admin.sections.edit', $schedule) }}" class="text-xs w-16 text-center hover:underline bg-yellow-600 rounded-sm px-3 py-2">Edit</a>
                        
                        <form action="{{ route('admin.sections.destroy', $schedule) }}" method="POST" class="inline m-0 p-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs w-16 text-center hover:underline bg-red-600 rounded-sm px-3 py-2" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>

                </div>
            </li>
            @empty
                <!-- empty -->
                <li class="text-center text-gray-500 py-10 bg-gray-50 rounded-md border border-dashed border-gray-300">
                    No schedules found.
                </li>
            @endforelse
        </ul>

        {{ $schedules->links() }}
    </div>

</x-layouts.app>