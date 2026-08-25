<x-layouts.app title="Registrar | Enrolled Student" header="Enrolled Student Details" class="p-4 md:p-6">

    {{-- Top Action Bar --}}
    <div class="flex justify-between items-center mb-4 px-2 md:px-0">
        <a href="{{ route('registrar.enrolled.index') }}" 
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-900 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to Enrolled Students
        </a>
        
        {{-- print button placeholder
        <button onclick="window.print()" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 shadow-sm transition-colors">
            <x-heroicon-o-printer class="w-4 h-4 mr-2" />
            Print Profile
        </button> --}}
        
    </div>

    {{-- Main Profile Container --}}
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-8">
        
        {{-- Header Section --}}
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">
                    {{ $enrollment->studentProfile->last_name }}, {{ $enrollment->studentProfile->first_name }} {{ $enrollment->studentProfile->middle_name ?? '' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Officially Enrolled: {{ $enrollment->updated_at->format('F d, Y h:i A') }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 uppercase tracking-wide border border-green-200 flex items-center gap-1">
                <x-heroicon-s-check-circle class="w-4 h-4" />
                Enrolled
            </span>
        </div>

        {{-- Details Body --}}
        <div class="p-6 space-y-10">
            
            {{-- Section 1: Enrollment Specs --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Academic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">LRN</span>
                        <span class="text-gray-900 font-medium">{{ $enrollment->studentProfile->lrn }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Grade Level</span>
                        <span class="text-gray-900 font-medium">{{ $enrollment->grade_level === 'Kinder' ? 'Kindergarten' : 'Grade ' . $enrollment->grade_level }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Application Type</span>
                        <span class="text-gray-900 font-medium capitalize">{{ str_replace('_', ' ', $enrollment->application_type) }}</span>
                    </div>
                </div>
            </section>

            {{-- Section 2: Personal Information --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Date of Birth</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($enrollment->studentProfile->date_of_birth)->format('F d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Gender</span>
                        <span class="text-gray-900 capitalize">{{ $enrollment->studentProfile->gender }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Contact Number</span>
                        <span class="text-gray-900">{{ $enrollment->studentProfile->contact_number ?? 'N/A' }}</span>
                    </div>
                    <div class="md:col-span-3">
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Home Address</span>
                        <span class="text-gray-900">{{ $enrollment->studentProfile->address }}</span>
                    </div>
                </div>
            </section>

            {{-- Section 3: Educational Background --}}
            @if($enrollment->educationalBackground)
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Educational Background</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Previous School</span>
                        <span class="text-gray-900">{{ $enrollment->educationalBackground->previous_school_name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">School Year</span>
                        <span class="text-gray-900">{{ $enrollment->educationalBackground->school_year ?? 'N/A' }}</span>
                    </div>
                </div>
            </section>
            @endif

            {{-- Grid for Documents and Payments --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- Section 4: Verified Documents --}}
                <section class="bg-blue-50/50 p-5 rounded-lg border border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-900 border-b border-blue-200 pb-2 mb-4 flex items-center">
                        <x-heroicon-o-document-check class="w-5 h-5 mr-2" />
                        Verified Documents
                    </h3>
                    <ul class="space-y-3">
                        @forelse($enrollment->submittedDocuments as $document)
                            <li class="flex items-start text-sm text-gray-700 bg-white p-2.5 rounded shadow-sm border border-gray-100">
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 mr-2 shrink-0" />
                                <span>{{ $document->name }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500 italic px-2">No documents verified for this student.</li>
                        @endforelse
                    </ul>
                </section>

                {{-- Section 5: Payment Records --}}
                <section class="bg-green-50/50 p-5 rounded-lg border border-green-100">
                    <h3 class="text-lg font-semibold text-green-900 border-b border-green-200 pb-2 mb-4 flex items-center">
                        <x-heroicon-o-banknotes class="w-5 h-5 mr-2" />
                        Enrollment Payment
                    </h3>
                    @if($enrollment->payment && $enrollment->payment->status === 'paid')
                        <div class="space-y-4">
                            <div>
                                <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Official Receipt (O.R.) No.</span>
                                <span class="text-gray-900 font-bold text-lg font-mono bg-white px-2 py-1 rounded shadow-sm border border-gray-100">{{ $enrollment->payment->or_number }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Amount Paid</span>
                                    <span class="text-gray-900 font-medium">₱ {{ number_format($enrollment->payment->amount_paid, 2) }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Payment Method</span>
                                    <span class="text-gray-900 capitalize">{{ $enrollment->payment->payment_method }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Payment Scheme</span>
                                    <span class="text-gray-900 capitalize">{{ str_replace('_', ' ', $enrollment->payment_scheme) }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Date Paid</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($enrollment->payment->updated_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-red-600 font-medium bg-red-50 p-3 rounded border border-red-100">
                            Warning: Missing payment record or payment not finalized.
                        </p>
                    @endif
                </section>
                
            </div>
        </div>
    </div>
</x-layouts.app>