<x-form.layout class="py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg text-center">
        
        {{-- check icon and header --}}
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <x-heroicon-s-check-circle class="h-10 w-10 text-green-600" />
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Application Submitted!</h1>
        <p class="text-gray-600 mb-8">
            Application submitted successfully. Please check your email and phone for your reference code, which you'll need to track your application status and submit the required documents.
        </p>

        {{-- reference code --}}
        @if($referenceCode)
            <div x-data="{ 
                    copied: false,
                    copyText(text) {
                        // Use Modern API if on HTTPS / Secure Context
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(text);
                        } else {
                            // Fallback for HTTP environments
                            const textArea = document.createElement('textarea');
                            textArea.value = text;
                            textArea.style.position = 'absolute';
                            textArea.style.left = '-999999px';
                            document.body.appendChild(textArea);
                            textArea.select();
                            try {
                                document.execCommand('copy');
                            } catch (error) {
                                console.error('Fallback copy failed', error);
                            } finally {
                                textArea.remove();
                            }
                        }
                        this.copied = true;
                        setTimeout(() => this.copied = false, 1500);
                    }
                }" 
                class="bg-orange-50 border-l-4 border-orange-500 p-5 mb-8 text-left rounded-r-md shadow-sm">
                
                <div class="flex items-start">
                    <div class="shrink-0 mt-0.5">
                        <x-heroicon-s-hashtag class="h-6 w-6 text-orange-600" />
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-lg font-bold text-orange-800">Your Reference Code</h3>
                        <div class="mt-2 text-sm text-orange-700">
                            <p>Please keep this code secure. You'll need it to track your application status.</p>
                            
                            {{-- Inner white box mirroring the document requirement block --}}
                            <div class="mt-4 p-4 bg-white border border-orange-200 rounded flex items-center justify-between gap-3">
                                <p class="text-2xl font-bold tracking-widest text-orange-900">{{ $referenceCode }}</p>
                                <button type="button"
                                        @click="copyText('{{ $referenceCode }}')"
                                        class="inline-flex items-center gap-1 px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors shrink-0">
                                    <x-heroicon-s-clipboard-document class="h-4 w-4" x-show="!copied" />
                                    <x-heroicon-s-check class="h-4 w-4 text-green-600" x-show="copied" x-cloak />
                                    <span x-text="copied ? 'Copied!' : 'Copy'"></span>
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
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-600 mb-8 text-left">
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