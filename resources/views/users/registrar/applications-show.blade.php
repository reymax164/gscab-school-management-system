<x-layouts.app title="Registrar | Application Details" header="Review Application" class="p-4 md:p-6">

    {{-- Top Action Bar --}}
    <div class="flex justify-end mb-4 px-2 md:px-0">
        <a href="{{ route('registrar.applications.index') }}" 
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-900 transition-colors">

            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to Applications List
        </a>
    </div>

    {{-- alpine wrapper for Modal --}}
    <div x-data="{ 
            showModal: false, 
            actionUrl: '', 
            actionMethod: 'PATCH',
            modalTitle: '', 
            modalMessage: '', 
            confirmClass: '', 
            confirmText: '',
            
            // Dynamic Checklist Logic (cast IDs to strings for strict array comparison)
            requiredDocs: {{ json_encode($requirements->pluck('id')->map(fn($id) => (string)$id)) }},
            checkedDocs: [],
            
            get canAdmit() {
                // Returns true ONLY if every required document ID is inside the checkedDocs array
                return this.requiredDocs.length > 0 && 
                    this.requiredDocs.every(id => this.checkedDocs.includes(String(id)));
            },

            openModal(type, url) {
                this.actionUrl = url;
                this.showModal = true;
                if (type === 'admit') {
                    this.modalTitle = 'Admit Student';
                    this.modalMessage = 'Are you sure you want to admit this student? They will be forwarded to the Cashier for payment processing.';
                    this.confirmClass = 'bg-green-600 hover:bg-green-700';
                    this.confirmText = 'Admit Student';
                } else {
                    this.modalTitle = 'Deny Application';
                    this.modalMessage = 'Are you sure you want to deny this application? This action cannot be easily undone.';
                    this.confirmClass = 'bg-red-600 hover:bg-red-700';
                    this.confirmText = 'Deny Application';
                }
            }
        }" 
        class="bg-white shadow-sm hover:shadow-md transition-shadow rounded-lg border border-gray-200 overflow-hidden mb-8">
        
        {{-- header section (name & badge) --}}
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">
                    {{ $enrollment->studentProfile->last_name }}, {{ $enrollment->studentProfile->first_name }} {{ $enrollment->studentProfile->middle_name ?? '' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Application Submitted: {{ $enrollment->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 uppercase tracking-wide border border-yellow-200">
                Pending Review
            </span>
        </div>

        {{-- details body --}}
        <div class="p-6 space-y-10">
            
            {{-- Section 1: Enrollment Specs --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Enrollment Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">LRN</span>
                        <span class="block mt-1 text-sm text-gray-900 font-medium">{{ $enrollment->studentProfile->lrn }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Grade Level</span>
                        <span class="block mt-1 text-sm text-gray-900 font-medium">Grade {{ $enrollment->grade_level }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Student Status</span>
                        <span class="block mt-1 text-sm text-gray-900 font-medium capitalize">{{ $enrollment->student_status }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Scheme</span>
                        <span class="block mt-1 text-sm text-gray-900 font-medium">{{ $enrollment->payment->payment_scheme ?? 'Not Specified' }}</span>
                    </div>
                </div>
            </section>

            {{-- Section 2: Personal Information --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Gender</span>
                        <span class="block mt-1 text-sm text-gray-900 capitalize">{{ $enrollment->studentProfile->gender }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Birthdate</span>
                        <span class="block mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($enrollment->studentProfile->birthdate)->format('F d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Age</span>
                        <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->age }} yrs old</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Religion</span>
                        <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->religion ?? '-' }}</span>
                    </div>
                    <div class="md:col-span-4">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Complete Address</span>
                        <span class="block mt-1 text-sm text-gray-900">
                            {{ $enrollment->studentProfile->house_no }}, {{ $enrollment->studentProfile->sitio_subdivision }}, Brgy. {{ $enrollment->studentProfile->barangay }} (Zip: {{ $enrollment->studentProfile->zip }})
                        </span>
                    </div>
                </div>
            </section>

            {{-- Section 3: Family Details --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Family Background</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Father --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <span class="block font-bold text-gray-800 mb-3 border-b border-gray-200 pb-1">Father's Details</span>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Name</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->father_details['first_name'] ?? '-' }} {{ $enrollment->studentProfile->father_details['last_name'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Status</span>
                                <span class="block mt-1 text-sm text-gray-900 capitalize">{{ $enrollment->studentProfile->father_details['deceased'] === 'yes' ? 'Deceased' : 'Living' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Contact No.</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->father_details['number'] ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Occupation</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->father_details['occupation'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Mother --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <span class="block font-bold text-gray-800 mb-3 border-b border-gray-200 pb-1">Mother's Details</span>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Name</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->mother_details['first_name'] ?? '-' }} {{ $enrollment->studentProfile->mother_details['maiden_last'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Status</span>
                                <span class="block mt-1 text-sm text-gray-900 capitalize">{{ $enrollment->studentProfile->mother_details['deceased'] === 'yes' ? 'Deceased' : 'Living' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Contact No.</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->mother_details['number'] ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">Occupation</span>
                                <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->studentProfile->mother_details['occupation'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            {{-- Section 4: Educational Background --}}
            @if($enrollment->educationalBackground)
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Educational Background</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Previous School</span>
                        <span class="block mt-1 text-sm text-gray-900">{{ $enrollment->educationalBackground->last_school ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">General Average</span>
                        <span class="block mt-1 text-sm text-gray-900 font-medium">{{ $enrollment->educationalBackground->gen_ave ?? 'N/A' }}</span>
                    </div>
                </div>
            </section>
            @endif

        </div>

        {{-- Section 5: Document Verification Checklist --}}
        @if($requirements->isNotEmpty())
        <section class="bg-blue-50/50 p-6 rounded-lg border border-blue-100">
            <div class="flex items-center gap-2 mb-4 border-b-2 border-blue-200 pb-2">
                @svg('heroicon-s-clipboard-document-check', 'w-5 h-5 text-blue-700')
                <h3 class="text-lg font-semibold text-blue-900">Required Documents Checklist</h3>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">Please verify that the applicant has submitted the following physical documents before admitting them.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($requirements as $doc)
                <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-md cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors shadow-sm">
                    {{-- x-model binds this checkbox to the checkedDocs array --}}
                    <input type="checkbox" value="{{ $doc->id }}" x-model="checkedDocs" 
                        class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition-colors">
                    <span class="text-sm font-medium text-gray-800">{{ $doc->name }}</span>
                </label>
                @endforeach
            </div>
        </section>
        @endif

        {{-- footer action buttons --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 items-center">
            
            {{-- Helper text that shows up if they haven't checked everything --}}
            <span x-show="!canAdmit && requiredDocs.length > 0" class="text-sm font-medium text-red-500 mr-4 flex items-center gap-1" x-cloak>
                @svg('heroicon-m-information-circle', 'w-4 h-4')
                Complete checklist to admit
            </span>

            <button type="button" 
                    @click="openModal('deny', '{{ route('registrar.applications.deny', $enrollment->id) }}')" 
                    class="bg-white text-red-600 hover:bg-red-50 hover:text-red-700 border border-red-200 font-medium px-5 py-2.5 rounded-md transition-colors shadow-sm">
                Deny Application
            </button>
            
            {{-- Dynamic Admit Button --}}
            <button type="button" 
                    :disabled="!canAdmit"
                    @click="openModal('admit', '{{ route('registrar.applications.admit', $enrollment->id) }}')" 
                    :class="canAdmit ? 'bg-green-600 hover:bg-green-700 text-white cursor-pointer' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                    class="font-medium px-5 py-2.5 rounded-md transition-colors shadow-sm flex items-center gap-2">
                Admit Student
            </button>
        </div>

        {{-- alpine action modal --}}
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div @click.away="showModal = false" 
                 class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden"
                 x-transition:enter="transition ease-out duration-150 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-100 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" x-text="modalTitle"></h3>
                </div>
                
                <div class="px-6 py-4 text-gray-600" x-text="modalMessage"></div>
                
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <form :action="actionUrl" method="POST">
                            @csrf
                            <input type="hidden" name="_method" :value="actionMethod">
                            
                            <template x-for="docId in checkedDocs" :key="docId">
                                <input type="hidden" name="submitted_documents[]" :value="docId">
                            </template>
                        @method('PATCH')
                        <button type="submit" :class="confirmClass" class="px-4 py-2 text-sm font-medium text-white rounded-md transition-colors shadow-sm" x-text="confirmText"></button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>