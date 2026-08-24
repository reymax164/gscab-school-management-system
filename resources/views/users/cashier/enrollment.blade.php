<x-layouts.app title="Cashier | Pending Enrollments" header="Pending Enrollments" class="p-4 md:p-6">

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        
        {{-- table toolbar (Search / Filter placeholders can go here) --}}
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Awaiting Initial Payment</h3>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                {{ $applications->total() }} Pending
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">Full Name</th>
                        <th scope="col" class="px-6 py-4 font-bold">Grade</th>
                        <th scope="col" class="px-6 py-4 font-bold">LRN</th>
                        <th scope="col" class="px-6 py-4 font-bold">Scheme</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Tuition</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Books & Misc</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right text-blue-900">Total Balance</th>
                        <th scope="col" class="px-6 py-4 text-center font-bold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            {{-- Identity --}}
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $application->studentProfile->last_name }}, {{ $application->studentProfile->first_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">Grade {{ $application->grade_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->studentProfile->lrn }}</td>
                            
                            {{-- Payment Data (Assuming these columns exist in your Payment model) --}}
                            <td class="px-6 py-4 whitespace-nowrap font-medium capitalize">
                                {{ $application->payment->payment_scheme ?? 'N/A' }}
                            </td>
                            
                            {{-- Financials --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                ₱{{ number_format($application->payment->tuition_fee ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                ₱{{ number_format(($application->payment->books_fee ?? 0) + ($application->payment->misc_fee ?? 0), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-blue-700">
                                ₱{{ number_format($application->payment->total_amount ?? 0, 2) }}
                            </td>
                            
                            {{-- Action --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('cashier.enrollment.show', $application->id) }}" 
                                   class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors shadow-sm">
                                    Process Payment
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    @svg('heroicon-o-check-circle', 'w-12 h-12 text-green-400 mb-3')
                                    <p class="text-lg font-medium text-gray-900">All caught up!</p>
                                    <p class="text-sm">There are no pending enrollments awaiting payment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- pagination --}}
        @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-white">
                {{ $applications->links() }}
            </div>
        @endif
        
    </div>
</x-layouts.app>