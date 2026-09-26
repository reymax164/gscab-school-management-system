<x-layouts.app title="Admin | Registrars" header="Manage Registrars" class="p-4 md:p-6">

{{-- filter & sort form --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-6 px-2 md:px-0">

        <form action="{{ route('admin.accounts.registrar.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-start sm:items-end gap-3 flex-wrap">

            {{-- sort by --}}
            <x-form.select label="Sort by" name="sort_by" onchange="this.form.submit()">
                <option value="last_name" {{ request('sort_by', 'last_name') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
            </x-form.select>

            {{-- order --}}
            <x-form.select label="Order" name="sort_dir" onchange="this.form.submit()">
                <option value="asc" {{ request('sort_dir', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Descending</option>
            </x-form.select>

            {{-- clear button --}}
            @if(request()->filled('sort_by') || request()->filled('sort_dir'))
                <a href="{{ route('admin.accounts.registrar.index') }}"
                   class="inline-flex justify-center items-center text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 text-sm font-medium transition-colors rounded-md px-3 py-2.5 h-10.5 shrink-0">
                    Clear
                </a>
            @endif
        </form>

        {{-- add registrar --}}
        <a href="{{ route('admin.accounts.registrar.create') }}"
           class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-md shadow-sm transition-colors shrink-0">
            Add Registrar
        </a>
    </div>

    {{-- TODO: routes/admin.php uses an inline closure with no controller; $registrars (paginated User list where role = registrar) is not yet supplied --}}
    <x-table :headers="['Full Name', 'Email', 'Role', '']">
        @forelse ($registrars as $registrar)
            <x-table.row>
                <x-table.cell bold>
                    {{ $registrar->last_name }}, {{ $registrar->first_name }}
                    @if($registrar->middle_name)
                        {{ strtoupper(substr($registrar->middle_name, 0, 1)) }}.
                    @endif
                </x-table.cell>
                <x-table.cell>{{ $registrar->email }}</x-table.cell>
                <x-table.cell class="capitalize">{{ $registrar->role }}</x-table.cell>
                <x-table.cell class="text-right">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Archive button (no function yet) --}}
                        <button type="button"
                                class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-sm font-medium px-4 py-2 rounded-md transition-colors">
                            Archive
                        </button>

                        {{-- View Details Button --}}
                        <a href="{{ route('admin.accounts.registrar.show', $registrar->id) }}"
                           class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors shadow-sm">
                            View Details
                        </a>
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="4" class="text-center text-neutral-500 py-8">No registrar accounts found.</x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>

    {{-- pagination --}}
    @if($registrars->hasPages())
        <div class="mt-4">
            {{ $registrars->links() }}
        </div>
    @endif

</x-layouts.app>
