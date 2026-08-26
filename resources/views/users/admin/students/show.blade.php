<x-layouts.app title="Admin | Student Details" header="Student Details" class="p-4 md:p-6">

    {{-- Top Action Bar --}}
    <div class="flex justify-between items-center mb-4 px-2 md:px-0">
        <a href="{{ route('admin.students') }}"
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-900 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to Students
        </a>

        <a href="{{ route('admin.students.edit', $enrollment->id) }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-yellow-900 bg-yellow-400 hover:bg-yellow-500 rounded-md shadow-sm transition-colors">
            <x-heroicon-o-pencil-square class="w-4 h-4 mr-2" />
            Edit
        </a>
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

            {{-- Section 1: Account Details --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Account Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email Address</span>
                        <span class="text-gray-900 font-medium">{{ $enrollment->user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Password</span>
                        <span class="text-gray-900 font-medium tracking-widest">************</span>
                    </div>
                </div>
            </section>

            {{-- Section 2: Academic Information --}}
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
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Student Type</span>
                        <span class="text-gray-900 font-medium capitalize">{{ $enrollment->student_status }}</span>
                    </div>
                </div>
            </section>

            {{-- Section 3: Personal Information --}}
            <section>
                <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Date of Birth</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($enrollment->studentProfile->birthdate)->format('F d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Gender</span>
                        <span class="text-gray-900 capitalize">{{ $enrollment->studentProfile->gender }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Age</span>
                        <span class="text-gray-900">{{ $enrollment->studentProfile->age }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Birthplace</span>
                        <span class="text-gray-900">{{ $enrollment->studentProfile->birthplace ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Guardian</span>
                        <span class="text-gray-900">{{ $enrollment->studentProfile->guardian_details['name'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Contact Number</span>
                        <span class="text-gray-900">{{ $enrollment->studentProfile->contact_person['number'] ?? 'N/A' }}</span>
                    </div>
                    <div class="md:col-span-3">
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Home Address</span>
                        <span class="text-gray-900">
                            {{ collect([
                                $enrollment->studentProfile->house_no,
                                $enrollment->studentProfile->sitio_subdivision,
                                $enrollment->studentProfile->barangay,
                                $enrollment->studentProfile->zip,
                            ])->filter()->implode(', ') }}
                        </span>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-layouts.app>

