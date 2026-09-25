<x-layouts.app title="Registrar | Admissions" header="Applications List" class="p-4 md:p-6">

{{-- sort, filter, and walk-in button --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-6 px-2 md:px-0">
        
        {{-- filter & sort form --}}
        <form action="{{ route('registrar.applications.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row items-start sm:items-end gap-3 flex-wrap">
            
            {{-- grade level filter --}}
            <x-form.select label="Grade Level" name="grade_level" onchange="this.form.submit()">
                <option value="">All Grades</option>
                <option value="Kinder" {{ request('grade_level') == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('grade_level') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                @endfor
            </x-form.select>

            {{-- Sort By --}}
            <x-form.select label="Sort by" name="sort_by" onchange="this.form.submit()">
                <option value="last_name" {{ request('sort_by', 'last_name') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Submitted</option>
                <option value="lrn" {{ request('sort_by') == 'lrn' ? 'selected' : '' }}>LRN</option>
                <option value="grade_level" {{ request('sort_by') == 'grade_level' ? 'selected' : '' }}>Grade Enrolling for</option>
            </x-form.select>

            {{-- order --}}
            <x-form.select label="Order" name="sort_dir" onchange="this.form.submit()">
                <option value="asc" {{ request('sort_dir', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Descending</option>
            </x-form.select>

            {{-- clear button --}}
            @if(request()->filled('grade_level') || request()->filled('sort_by') || request()->filled('sort_dir'))
                <a href="{{ route('registrar.applications.index') }}" 
                   class="inline-flex justify-center items-center text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 text-sm font-medium transition-colors rounded-md px-3 py-2.5 h-10.5 shrink-0">
                    Clear
                </a>
            @endif
        </form>

        {{-- walk-in button --}}
        <a href="{{ route('registrar.applications.create') }}" 
           class="inline-flex justify-center items-center text-white text-sm font-medium bg-blue-900 hover:bg-blue-900/90 transition-colors rounded-md shadow-sm px-5 py-2.5 w-full sm:w-auto shrink-0">
           <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Add Walk-In
        </a>
    </div>

    {{-- alpine component wrapper for table and modal --}}
    <div x-data="{ 
            showModal: false, 
            actionUrl: '', 
            actionMethod: 'PATCH',
            modalTitle: '', 
            modalMessage: '', 
            confirmClass: '', 
            confirmText: '',
            
            openModal(type, url, name) {
                this.actionUrl = url;
                this.showModal = true;
                if (type === 'admit') {
                    this.modalTitle = 'Admit Student';
                    this.modalMessage = `Are you sure you want to admit ${name}?`;
                    this.confirmClass = 'bg-green-600 hover:bg-green-700';
                    this.confirmText = 'Admit';
                } else {
                    this.modalTitle = 'Deny Application';
                    this.modalMessage = `Are you sure you want to deny the application for ${name}?`;
                    this.confirmClass = 'bg-red-600 hover:bg-red-700';
                    this.confirmText = 'Deny';
                }
            }
        }" 
        class="bg-white shadow-sm hover:shadow-md transition-shadow rounded-lg border border-gray-200 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 text-blue-900 font-semibold border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">Last Name</th>
                        <th scope="col" class="px-6 py-4">First Name</th>
                        <th scope="col" class="px-6 py-4">Middle Name</th>
                        <th scope="col" class="px-6 py-4">LRN</th>
                        <th scope="col" class="px-6 py-4">Enrolling For</th>
                        <th scope="col" class="px-6 py-4">Date Submitted</th>
                        <th scope="col" class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
                    @forelse ($applications as $application)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- call the studentProfile relationship for personal details --}}
                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->studentProfile->last_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->studentProfile->first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->studentProfile->middle_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->studentProfile->lrn }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">Grade {{ $application->grade_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->created_at->format('M d, Y h:i A') }}</td>
                            
                            {{-- action buttons --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    {{-- Three-Dot Menu (Admit & Deny) --}}
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" 
                                                type="button" 
                                                class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors">
                                            <x-heroicon-m-ellipsis-vertical class="w-5 h-5" />
                                        </button>

                                        <div x-cloak
                                            x-show="open" 
                                            @click.away="open = false" 
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black/5 divide-y divide-gray-100 z-30">
                                            <div class="py-1">
                                                <button type="button" 
                                                        @click="open = false; openModal('admit', '{{ route('registrar.applications.admit', $application->id) }}', '{{ addslashes($application->studentProfile->first_name . ' ' . $application->studentProfile->last_name) }}')" 
                                                        class="group flex w-full items-center px-4 py-2 text-sm text-green-700 hover:bg-green-50 transition-colors">
                                                    <x-heroicon-o-check-circle class="w-4 h-4 mr-2 text-green-600" />
                                                    Admit
                                                </button>

                                                <button type="button" 
                                                        @click="open = false; openModal('deny', '{{ route('registrar.applications.deny', $application->id) }}', '{{ addslashes($application->studentProfile->first_name . ' ' . $application->studentProfile->last_name) }}')" 
                                                        class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                                    <x-heroicon-o-x-circle class="w-4 h-4 mr-2 text-red-600" />
                                                    Deny
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- View Details Button --}}
                                    <a href="{{ route('registrar.applications.show', $application->id) }}" 
                                    class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors shadow-sm">
                                        View Details
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No pending applications found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $applications->links() }}
            </div>
        @endif

        {{-- alpine action Modal --}}
        <div x-cloak x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-xs px-4"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div @click.away="showModal = false" 
                 class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden"
                 x-transition:enter="transition ease-out duration-15 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-10 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" x-text="modalTitle"></h3>
                </div>
                
                <div class="px-6 py-4 text-gray-600" x-text="modalMessage"></div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    
                    <form :action="actionUrl" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" :class="confirmClass" class="px-4 py-2 text-sm font-medium text-white rounded-md transition-colors" x-text="confirmText"></button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>