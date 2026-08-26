<x-layouts.app title="Admin | Edit Student" header="Edit Student" class="p-4 md:p-6">

    {{-- Top Action Bar --}}
    <div class="flex justify-between items-center mb-4 px-2 md:px-0">
        <a href="{{ route('admin.students.show', $enrollment->id) }}"
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-900 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to Student Details
        </a>
    </div>

    <form action="{{ route('admin.students.update', $enrollment->id) }}" method="POST">
        @csrf
        @method('PATCH')

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
                <section x-data="{
                    password: '',
                    generatePassword() {
                        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
                        let pass = '';
                        for (let i = 0; i < 12; i++) {
                            pass += chars.charAt(Math.floor(Math.random() * chars.length));
                        }
                        this.password = pass;
                    }
                }">
                    <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Account Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input label="Email Address" name="email" type="email" value="{{ old('email', $enrollment->user->email) }}" required />
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="flex gap-2">
                                <input type="text" name="password" id="password" x-model="password"
                                       placeholder="Leave blank to keep current password"
                                       class="flex-1 bg-white border border-neutral-300 rounded-md px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" @click="generatePassword()"
                                        class="shrink-0 inline-flex items-center px-3 py-2 text-sm font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition-colors">
                                    Generate Password
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section 2: Academic Information --}}
                <section>
                    <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Academic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-form.input label="LRN" name="lrn" value="{{ $enrollment->studentProfile->lrn }}" disabled />

                        <div class="flex flex-col gap-1">
                            <label for="grade_level" class="text-sm font-medium text-gray-700">Grade Level</label>
                            <select name="grade_level" id="grade_level" required
                                    class="bg-white border border-neutral-300 rounded-md px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="Kinder" {{ old('grade_level', $enrollment->grade_level) == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('grade_level', $enrollment->grade_level) == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label for="student_status" class="text-sm font-medium text-gray-700">Student Type</label>
                            <select name="student_status" id="student_status" required
                                    class="bg-white border border-neutral-300 rounded-md px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="new" {{ old('student_status', $enrollment->student_status) == 'new' ? 'selected' : '' }}>New</option>
                                <option value="existing" {{ old('student_status', $enrollment->student_status) == 'existing' ? 'selected' : '' }}>Existing</option>
                                <option value="transferee" {{ old('student_status', $enrollment->student_status) == 'transferee' ? 'selected' : '' }}>Transferee</option>
                            </select>
                        </div>
                    </div>
                </section>

                {{-- Section 3: Personal Information --}}
                <section>
                    <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Personal Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-form.input label="Last Name" name="last_name" value="{{ old('last_name', $enrollment->studentProfile->last_name) }}" required />
                        <x-form.input label="First Name" name="first_name" value="{{ old('first_name', $enrollment->studentProfile->first_name) }}" required />
                        <x-form.input label="Middle Name" name="middle_name" value="{{ old('middle_name', $enrollment->studentProfile->middle_name) }}" />

                        <x-form.input label="Date of Birth" name="birthdate" type="date" value="{{ old('birthdate', $enrollment->studentProfile->birthdate) }}" required />

                        <div class="flex flex-col gap-1">
                            <label for="gender" class="text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender" required
                                    class="bg-white border border-neutral-300 rounded-md px-3 py-2 text-sm text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="male" {{ old('gender', $enrollment->studentProfile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $enrollment->studentProfile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <x-form.input label="Age" name="age" type="number" min="0" max="120" value="{{ old('age', $enrollment->studentProfile->age) }}" required />

                        <x-form.input label="Birthplace" name="birthplace" value="{{ old('birthplace', $enrollment->studentProfile->birthplace) }}" />
                        <x-form.input label="Guardian" name="guardian_name" value="{{ old('guardian_name', $enrollment->studentProfile->guardian_details['name'] ?? null) }}" />
                        <x-form.input label="Contact Number" name="contact_number" value="{{ old('contact_number', $enrollment->studentProfile->contact_person['number'] ?? null) }}" />

                        <x-form.input label="House No." name="house_no" value="{{ old('house_no', $enrollment->studentProfile->house_no) }}" />
                        <x-form.input label="Sitio/Subdivision" name="sitio_subdivision" value="{{ old('sitio_subdivision', $enrollment->studentProfile->sitio_subdivision) }}" />
                        <x-form.input label="Barangay" name="barangay" value="{{ old('barangay', $enrollment->studentProfile->barangay) }}" />
                        <x-form.input label="Zip Code" name="zip" value="{{ old('zip', $enrollment->studentProfile->zip) }}" />
                    </div>
                </section>

            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.students.show', $enrollment->id) }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-md shadow-sm transition-colors">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-layouts.app>
