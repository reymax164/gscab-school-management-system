<x-layouts.app title="Admin | Students" header="Manage Students" class="p-4 md:p-6">

{{-- filter & sort form --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-6 px-2 md:px-0">

        <form action="{{ route('admin.accounts.students.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-start sm:items-end gap-3 flex-wrap">

            {{-- grade level filter --}}
            <x-form.select label="Grade Level" name="grade_level" onchange="this.form.submit()">
                <option value="">All Grades</option>
                <option value="Kinder" {{ request('grade_level') == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('grade_level') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                @endfor
            </x-form.select>

            {{-- sort by --}}
            <x-form.select label="Sort by" name="sort_by" onchange="this.form.submit()">
                <option value="last_name" {{ request('sort_by', 'last_name') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="lrn" {{ request('sort_by') == 'lrn' ? 'selected' : '' }}>LRN</option>
                <option value="grade_level" {{ request('sort_by') == 'grade_level' ? 'selected' : '' }}>Grade</option>
            </x-form.select>

            {{-- order --}}
            <x-form.select label="Order" name="sort_dir" onchange="this.form.submit()">
                <option value="asc" {{ request('sort_dir', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Descending</option>
            </x-form.select>

            {{-- clear button --}}
            @if(request()->filled('grade_level') || request()->filled('sort_by') || request()->filled('sort_dir'))
                <a href="{{ route('admin.accounts.students.index') }}"
                   class="inline-flex justify-center items-center text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 text-sm font-medium transition-colors rounded-md px-3 py-2.5 h-10.5 shrink-0">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white shadow-sm hover:shadow-md transition-shadow rounded-lg border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 text-blue-900 font-semibold border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">LRN</th>
                        <th scope="col" class="px-6 py-4">Full Name</th>
                        <th scope="col" class="px-6 py-4">Grade</th>
                        <th scope="col" class="px-6 py-4">Guardian</th>
                        <th scope="col" class="px-6 py-4">Contact Number</th>
                        <th scope="col" class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    @forelse ($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $student->studentProfile->lrn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $student->studentProfile->last_name }}, {{ $student->studentProfile->first_name }}
                                @if($student->studentProfile->middle_name)
                                    {{ strtoupper(substr($student->studentProfile->middle_name, 0, 1)) }}.
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->grade_level === 'Kinder' ? 'Kinder' : 'Grade '.$student->grade_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->studentProfile->guardian_details['name'] ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->studentProfile->contact_person['number'] ?? '-' }}</td>

                            {{-- action buttons --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Archive button (no function yet) --}}
                                    <button type="button"
                                            class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-sm font-medium px-4 py-2 rounded-md transition-colors">
                                        Archive
                                    </button>

                                    {{-- View Details Button --}}
                                    <a href="{{ route('admin.accounts.students.show', $student->id) }}"
                                    class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors shadow-sm">
                                        View Details
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No enrolled students found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        @if($students->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $students->links() }}
            </div>
        @endif

    </div>
</x-layouts.app>