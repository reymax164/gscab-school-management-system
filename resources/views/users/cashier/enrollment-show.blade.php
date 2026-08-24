<x-layouts.app title="Cashier | Process Payment" header="Process Downpayment" class="p-4 md:p-6">

    <div class="flex justify-end mb-4 px-2 md:px-0">
        <a href="{{ route('cashier.enrollment.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-900 transition-colors">
            @svg('heroicon-s-arrow-left', 'w-4 h-4 mr-1')
            Back to Pending Enrollments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- left column: financial breakdown --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="border-b border-gray-100 pb-4 mb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $enrollment->studentProfile->last_name }}, {{ $enrollment->studentProfile->first_name }}</h2>
                        <p class="text-sm text-gray-500">LRN: {{ $enrollment->studentProfile->lrn }} | Grade {{ $enrollment->grade_level }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 uppercase">
                        Pending Payment
                    </span>
                </div>

                <h3 class="text-md font-semibold text-gray-800 mb-3">Fee Breakdown</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-gray-600">Tuition Fee</span>
                        <span class="font-medium text-gray-900">₱{{ number_format($enrollment->payment->tuition_fee ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-gray-600">Miscellaneous Fee</span>
                        <span class="font-medium text-gray-900">₱{{ number_format($enrollment->payment->misc_fee ?? 0, 2) }}</span>
                    </div>
                    
                    @if($enrollment->payment->discount_amount > 0)
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded text-green-700">
                        <span>Discount Applied</span>
                        <span class="font-medium">- ₱{{ number_format($enrollment->payment->discount_amount, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg mt-4 border border-blue-100">
                        <span class="font-bold text-blue-900 text-base">Total Amount Due</span>
                        <span class="font-bold text-blue-900 text-xl">₱{{ number_format($enrollment->payment->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Processing Form --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 h-fit">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Process Transaction</h3>
            
            <form action="{{ route('cashier.enrollment.process', $enrollment->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="payment_scheme" class="block text-sm font-medium text-gray-700 mb-1">Payment Scheme</label>
                    <input type="text" disabled value="{{ ucfirst($enrollment->payment->payment_scheme) }}" class="w-full bg-gray-100 border border-gray-300 text-gray-600 text-sm rounded-md px-3 py-2">
                </div>

                {{-- payment method --}}
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm rounded-md px-3 py-2 bg-white">
                        <option value="" disabled selected>Select method...</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Check">Check</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>
                    @error('payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- receipt --}}
                <div>
                    <label for="or_number" class="block text-sm font-medium text-gray-700 mb-1">Official Receipt (O.R.) Number <span class="text-red-500">*</span></label>
                    <input type="text" name="or_number" id="or_number" required placeholder="e.g. OR-102938" 
                           class="w-full border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm rounded-md px-3 py-2">
                    @error('or_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md transition-colors shadow-sm flex justify-center items-center gap-2">
                        @svg('heroicon-s-check-circle', 'w-5 h-5')
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>