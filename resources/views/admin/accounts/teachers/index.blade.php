<x-layouts.app title="Admin | Teachers" header="Manage Teachers" class="p-4 md:p-6">

{{-- filter & sort form --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-6 px-2 md:px-0">

        <form action="{{ route('admin.accounts.teachers.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-start sm:items-end gap-3 flex-wrap">

            {{-- department filter --}}
            <x-form.select label="Department" name="department" onchange="this.form.submit()">
                <option value="">All Departments</option>
                @foreach (['Mathematics', 'Science', 'English', 'History', 'Mapeh'] as $department)
                    <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
                @endforeach
            </x-form.select>

            {{-- sort by --}}
            <x-form.select label="Sort by" name="sort_by" onchange="this.form.submit()">
                <option value="last_name" {{ request('sort_by', 'last_name') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="employee_id" {{ request('sort_by') == 'employee_id' ? 'selected' : '' }}>Employee ID</option>
                <option value="hire_date" {{ request('sort_by') == 'hire_date' ? 'selected' : '' }}>Hire Date</option>
            </x-form.select>

            {{-- order --}}
            <x-form.select label="Order" name="sort_dir" onchange="this.form.submit()">
                <option value="asc" {{ request('sort_dir', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Descending</option>
            </x-form.select>

            {{-- clear button --}}
            @if(request()->filled('department') || request()->filled('sort_by') || request()->filled('sort_dir'))
                <a href="{{ route('admin.accounts.teachers.index') }}"
                   class="inline-flex justify-center items-center text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 text-sm font-medium transition-colors rounded-md px-3 py-2.5 h-10.5 shrink-0">
                    Clear
                </a>
            @endif
        </form>

        {{-- add teacher --}}
        <a href="{{ route('admin.accounts.teachers.create') }}"
           class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-md shadow-sm transition-colors shrink-0">
            Add Teacher
        </a>
    </div>

    {{-- TODO: controller does not yet supply $teachers (paginated Teacher list with user relation) --}}
    <x-table :headers="['Employee ID', 'Full Name', 'Department', 'Hire Date', '']">
        @forelse ($teachers as $teacher)
            <x-table.row>
                <x-table.cell bold class="font-mono">{{ $teacher->employee_id }}</x-table.cell>
                <x-table.cell>
                    {{ $teacher->user->last_name }}, {{ $teacher->user->first_name }}
                    @if($teacher->user->middle_name)
                        {{ strtoupper(substr($teacher->user->middle_name, 0, 1)) }}.
                    @endif
                </x-table.cell>
                <x-table.cell>{{ $teacher->department_id ?? '-' }}</x-table.cell>
                <x-table.cell>{{ \Carbon\Carbon::parse($teacher->hire_date)->format('F d, Y') }}</x-table.cell>
                <x-table.cell class="text-right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Archive button (no function yet) --}}
                        <button type="button"
                                class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-sm font-medium px-4 py-2 rounded-md transition-colors">
                            Archive
                        </button>

                        {{-- View Details Button --}}
                        <a href="{{ route('admin.accounts.teachers.show', $teacher->id) }}"
                           class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors shadow-sm">
                            View Details
                        </a>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="5" class="text-center text-neutral-500 py-8">No teachers found.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>

    {{-- pagination --}}
    @if($teachers->hasPages())
        <div class="mt-4">
            {{ $teachers->links() }}
        </div>
    @endif

</x-layouts.app>
