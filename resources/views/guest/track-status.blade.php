<x-form.layout title="Track Application Status">
    {{-- Fixed centering (mx-auto), squared corners (rounded-none), and standardized border --}}
    <div class="relative w-full max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg border-t-blue-900 border-t-6">
        
        {{-- header --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Track Application Status</h1>
            <p class="text-sm text-gray-600">Enter your Reference Code below to check the current status of your enrollment.</p>
        </div>

        {{-- tracking Form --}}
        <form action="{{ route('track.check') }}" method="POST" class="mb-8">
            @csrf
            
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="grow">
                    <label for="reference_code" class="sr-only">Reference Code</label>
                    <input type="text" 
                           name="reference_code" 
                           id="reference_code" 
                           value="{{ old('reference_code') }}"
                           placeholder="e.g. APP-2026-ABCDEF" 
                           required 
                           class="w-full border-gray-300 rounded-sm shadow-sm px-4 py-2.5 uppercase focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 placeholder-gray-400">
                </div>
                
                <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-900 text-white font-semibold rounded-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 transition-colors">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 mr-2" />
                    Check
                </button>
            </div>
            
            @error('reference_code')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </form>

        {{-- error message display (Squared off to match convention) --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-none shadow-sm">
                <div class="flex items-center">
                    <x-heroicon-s-x-circle class="h-5 w-5 text-red-500 mr-2" />
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- status display (Squared off) --}}
        @if(session('status_result'))
            <div class="bg-gray-50 border border-gray-200 rounded-none shadow-sm p-6 text-center">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Current Status</h3>
                
                @php
                    $status = session('status_result');
                    
                    // map database statuses to human-readable text and colors
                    $statusConfig = match($status) {
                        'submitted' => ['text' => 'Application Submitted', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'heroicon-s-document-text', 'desc' => 'Pending physical document verification.'],
                        'registrar_approved' => ['text' => 'Registrar Approved', 'color' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'heroicon-s-clipboard-document-check', 'desc' => 'Documents verified. Proceed to cashier for payment.'],
                        'cashier_cleared' => ['text' => 'Payment Cleared', 'color' => 'bg-indigo-100 text-indigo-800 border-indigo-200', 'icon' => 'heroicon-s-banknotes', 'desc' => 'Payment received. Waiting for final enrollment processing.'],
                        'enrolled' => ['text' => 'Officially Enrolled', 'color' => 'bg-green-100 text-green-800 border-green-200', 'icon' => 'heroicon-s-check-badge', 'desc' => 'Welcome to GSCAB! Your enrollment is complete.'],
                        'rejected' => ['text' => 'Application Denied', 'color' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'heroicon-s-x-circle', 'desc' => 'Your application has been denied. Please contact the registrar.'],
                        default => ['text' => 'Unknown Status', 'color' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'heroicon-s-question-mark-circle', 'desc' => 'Please contact support.']
                    };
                @endphp

                <div class="flex flex-col items-center justify-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-none border font-bold text-sm tracking-wide {{ $statusConfig['color'] }}">
                        @svg($statusConfig['icon'], 'w-5 h-5 mr-2')
                        {{ $statusConfig['text'] }}
                    </span>
                    <p class="text-gray-600 text-sm mt-4 font-medium">{{ $statusConfig['desc'] }}</p>
                </div>
            </div>
        @endif
        
        {{-- navigation footer --}}
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm font-medium text-blue-800 hover:text-blue-600 transition-colors">
                Return to Homepage
            </a>
        </div>
        
    </div>
</x-form.layout>