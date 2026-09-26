<x-layouts.app title="Admin | Edit Registrar" header="Edit Registrar" class="p-4 md:p-6">

    {{-- Top Action Bar --}}
    <div class="flex justify-between items-center mb-4 px-2 md:px-0">
        <a href="{{ route('admin.accounts.registrar.show', $registrar->id) }}"
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-900 transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to Registrar Details
        </a>
    </div>

    {{-- TODO: routes/admin.php uses an inline closure with no controller; admin.accounts.registrar.update does not exist yet --}}
    <form action="{{ route('admin.accounts.registrar.update', $registrar->id) }}" method="POST">
        @csrf
        @method('PATCH')

        {{-- Main Profile Container --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-8">

            {{-- Header Section --}}
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">
                        {{ $registrar->last_name }}, {{ $registrar->first_name }} {{ $registrar->middle_name ?? '' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Account created: {{ $registrar->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 uppercase tracking-wide border border-green-200 flex items-center gap-1">
                    <x-heroicon-s-check-circle class="w-4 h-4" />
                    Active
                </span>
            </div>

            {{-- Details Body --}}
            <div class="p-6 space-y-10">

            {{-- Section 1: Account Details --}}
                <section x-data="{
                    password: '',
                    visible: false,
                    copied: false,
                    generatePassword() {
                        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
                        let pass = '';
                        for (let i = 0; i < 12; i++) {
                            pass += chars.charAt(Math.floor(Math.random() * chars.length));
                        }
                        this.password = pass;
                    },
                    revealPassword() {
                        this.visible = true;
                        setTimeout(() => { this.visible = false }, 1500);
                    },
                    copyPassword() {
                        if (!this.password) return;

                        const triggerSuccess = () => {
                            this.copied = true;
                            setTimeout(() => { this.copied = false }, 1500);
                        };

                        // Use modern clipboard API if available and secure
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(this.password).then(triggerSuccess);
                        } else {
                            // Fallback for non-HTTPS dev environments
                            let textArea = document.createElement('textarea');
                            textArea.value = this.password;
                            textArea.style.position = 'fixed';
                            textArea.style.opacity = '0';
                            document.body.appendChild(textArea);
                            textArea.focus();
                            textArea.select();
                            try {
                                document.execCommand('copy');
                                triggerSuccess();
                            } catch (err) {
                                console.error('Fallback: Oops, unable to copy', err);
                            }
                            document.body.removeChild(textArea);
                        }
                    }
                }">
                    <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Account Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input label="Email Address" name="email" type="email" value="{{ old('email', $registrar->email ?? 'N/A') }}" required />
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input :type="visible ? 'text' : 'password'" name="password" id="password" x-model="password" readonly
                                           placeholder="Use Generate Password to set a new password"
                                           class="w-full bg-white border border-neutral-300 rounded-md pl-3 pr-10 py-2 text-sm text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" @click="revealPassword()"
                                            class="absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-400 hover:text-gray-600">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </button>
                                </div>

                                {{-- Copy Button with Tooltip Indicator --}}
                                <div class="relative flex items-center">
                                    <button type="button" @click="copyPassword()" title="Copy password"
                                            class="shrink-0 inline-flex items-center justify-center w-9 h-9 text-gray-500 bg-white hover:bg-gray-50 border border-neutral-300 rounded-md transition-colors relative">
                                        <x-heroicon-o-clipboard-document class="w-4 h-4" x-show="!copied" />
                                        <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-green-600" x-show="copied" x-cloak />
                                    </button>

                                    {{-- "Copied!" Floating Indicator --}}
                                    <div x-show="copied"
                                         x-transition.opacity.duration.300ms
                                         x-cloak
                                         class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow z-10 whitespace-nowrap pointer-events-none">
                                        Copied!
                                    </div>
                                </div>

                                <button type="button" @click="generatePassword()"
                                        class="shrink-0 inline-flex items-center px-3 py-2 text-sm font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition-colors">
                                    Generate Password
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section 2: Personal Details --}}
                <section>
                    <h3 class="text-lg font-semibold text-blue-900 border-b-2 border-gray-100 pb-2 mb-4">Personal Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-form.input label="Last Name" name="last_name" value="{{ old('last_name', $registrar->last_name) }}" required />
                        <x-form.input label="First Name" name="first_name" value="{{ old('first_name', $registrar->first_name) }}" required />
                        <x-form.input label="Middle Name" name="middle_name" value="{{ old('middle_name', $registrar->middle_name) }}" />
                        <x-form.input label="Role" name="role_display" value="{{ ucfirst($registrar->role) }}" disabled />
                    </div>
                </section>

            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.accounts.registrar.show', $registrar->id) }}"
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
