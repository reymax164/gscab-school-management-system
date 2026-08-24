<x-layouts.app title="Registrar | Student Records" header="Student Records" class="p-4 md:p-6">

    {{-- Filter & Search Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="text-lg font-semibold text-gray-800">Student Roster</h2>
        
        <form action="{{ route('registrar.records.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <label for="grade_level" class="text-sm font-medium text-gray-700 sr-only">Filter by Grade</label>
            <select name="grade_level" id="grade_level" 
                    class="border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md text-sm px-3 py-2 w-full sm:w-48"
                    onchange="this.form.submit()">
                <option value="">All Grades</option>
                <option value="Kinder" {{ request('grade_level') == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('grade_level') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                @endfor
            </select>
            
            @if(request()->filled('grade_level'))
                <a href="{{ route('registrar.records.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium px-2">
                    Clear
                </a>
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
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold border border-blue-100">
                                    {{ $enrollment->grade_level === 'Kinder' ? 'Kinder' : 'Grade ' . $enrollment->grade_level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $enrollment->studentProfile->lrn }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('registrar.records.show', $enrollment->id) }}" 
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