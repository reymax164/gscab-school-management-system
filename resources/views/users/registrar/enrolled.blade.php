<x-layouts.app title="Registrar | Enrolled Students" header="Enrolled Students" class="p-4 md:p-6">

{{-- Filters & Sorting --}}
    <div class="mb-6">
        <form action="{{ route('registrar.enrolled.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4 w-full">
            
            {{-- Grade Filter --}}
            <div class="w-full sm:w-48">
                <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
                <x-form.select name="grade_level" id="grade_level" onchange="this.form.submit()">
                    <option value="">All Grades</option>
                    <option value="Kinder" {{ request('grade_level') == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('grade_level') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                    @endfor
                </x-form.select>
            </div>

            {{-- Sort By --}}
            <div class="w-full sm:w-48">
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <x-form.select name="sort_by" id="sort_by" onchange="this.form.submit()">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Enrollment Date</option>
                    <option value="grade_level" {{ request('sort_by') == 'grade_level' ? 'selected' : '' }}>Grade Level</option>
                </x-form.select>
            </div>

            {{-- Order --}}
            <div class="w-full sm:w-48">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <x-form.select name="order" id="order" onchange="this.form.submit()">
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                </x-form.select>
            </div>

            {{-- Clear Filters Button --}}
            @if(request()->anyFilled(['grade_level', 'sort_by', 'order']))
                <div class="w-full sm:w-auto">
                    <a href="{{ route('registrar.enrolled.index') }}" 
                       class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 border border-transparent rounded-md transition-colors w-full sm:w-auto h-fit">
                        Clear Filters
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Student Data Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-600 font-semibold">
                        <th class="px-6 py-4">Last Name</th>
                        <th class="px-6 py-4">First Name</th>
                        <th class="px-6 py-4">Middle Name</th>
                        <th class="px-6 py-4">Grade</th>
                        <th class="px-6 py-4">LRN</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($enrollments as $enrollment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $enrollment->studentProfile->last_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $enrollment->studentProfile->first_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $enrollment->studentProfile->middle_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $enrollment->grade_level === 'Kinder' ? 'Kinder' : 'Grade ' . $enrollment->grade_level }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $enrollment->studentProfile->lrn }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('registrar.enrolled.show', $enrollment->id) }}" 
                                   class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors border border-blue-200">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    @svg('heroicon-o-users', 'w-10 h-10 text-gray-300 mb-2')
                                    <p class="text-sm font-medium">No enrolled students found.</p>
                                    <p class="text-xs text-gray-400 mt-1">Try adjusting your grade filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($enrollments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>

</x-layouts.app>