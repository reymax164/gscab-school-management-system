<x-form.layout class="py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg text-center">
        
        {{-- check icon and header --}}
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <x-heroicon-s-check-circle class="h-10 w-10 text-green-600" />
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Application Submitted!</h1>
        <p class="text-gray-600 mb-8">
            Application submitted successfully. Please remember your reference code for tracking your application status and submit the required documents.
        </p>

        {{-- reference code --}}
        @if(session('reference_code'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-5 mb-8 text-left rounded-r-md shadow-sm" 
                x-data="{ 
                    copied: false,
                    copyToClipboard(text) {
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(text);
                        } else {
                            // Fallback for HTTP / .test local domains
                            let tempInput = document.createElement('input');
                            tempInput.value = text;
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand('copy');
                            document.body.removeChild(tempInput);
                        }
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2500);
                    }
                }">
                <div class="flex items-start">
                    <div class="shrink-0 mt-0.5">
                        <x-heroicon-s-exclamation-triangle class="h-6 w-6 text-yellow-600" />
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-lg font-bold text-yellow-800">Save Your Reference Code!</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Because email notifications are currently unavailable, you <strong>must</strong> copy and save this code. You will need it to track your admission status.</p>
                            
                            {{-- White Inner Div with Copy Button --}}
                            <div class="mt-4 p-3 bg-white border border-yellow-200 rounded flex items-center justify-between transition-colors"
                                :class="copied ? 'border-green-300 bg-green-50/30' : ''">
                                <span class="text-2xl font-mono font-bold tracking-wider transition-colors"
                                    :class="copied ? 'text-green-800' : 'text-gray-900'">
                                    {{ session('reference_code') }}
                                </span>
                                
                                <button type="button"
                                    @click="copyToClipboard('{{ session('reference_code') }}')"
                                    class="inline-flex items-center px-3 py-1.5 border shadow-sm text-sm font-medium rounded focus:outline-none transition-all duration-200"
                                    :class="copied ? 'bg-green-100 border-green-300 text-green-800' : 'border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100'"
                                >
                                    <x-heroicon-o-clipboard-document class="w-4 h-4 mr-1.5" x-show="!copied" />
                                    <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5 text-green-600" x-show="copied" style="display: none;" />
                                    <span x-text="copied ? 'Copied Successfully!' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- document checklist --}}
        @if($requirements->isNotEmpty())
            <div class="bg-blue-50 border-l-4 border-blue-500 p-5 mb-8 text-left rounded-r-md shadow-sm">
                <div class="flex items-start">
                    <div class="shrink-0 mt-0.5">
                        <x-heroicon-s-document-check class="h-6 w-6 text-blue-600" />
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-lg font-bold text-blue-800">Required Documents Checklist</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Please bring the following documents.</p>
                            
                            <div class="mt-4 p-4 bg-white border border-blue-200 rounded">
                                <ol class="space-y-4">
                                    @foreach($requirements as $doc)
                                        <li class="flex items-center text-sm font-medium text-gray-800">
                                            <span class="flex items-center justify-center w-6 h-6 text-blue-800 text-xs font-bold mr-3 shrink-0">
                                                {{ $loop->iteration }}.
                                            </span>
                                            {{ $doc->name }}
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-600 mb-8">
                No additional documents are required at this time.
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('track.form') }}" class="inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                View Application Status
            </a>
            
            <a href="{{ route('home') }}" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Return to Homepage
            </a>
        </div>
        
    </div>
</x-form.layout>