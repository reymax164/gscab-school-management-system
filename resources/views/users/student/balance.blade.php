<x-layouts.app title="Student | Balance" header="Balance" class="p-4 md:p-6">
    
    {{-- balance and filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="grow">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Balance</h2>

            {{-- total balance --}}
            <p class="text-3xl text-blue-900 dark:text-blue-400 font-bold">
                ₱{{ number_format($totalBalance ?? 0, 2) }}
            </p>
        </div>

        {{-- S.Y. filter --}}
        <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-auto">
            <x-form.select name="sy" label="S.Y." onchange="this.form.submit()">
                @for ($i = 2020; $i <= date('Y'); $i++)
                    <option value="{{ $i }}-{{ $i + 1 }}" @selected(request('sy') == "$i-".($i+1))>
                        {{ $i }} - {{ $i + 1 }}
                    </option>
                @endfor
            </x-form.select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        
        {{-- breakdown --}}
        <div class="flex flex-col space-y-3">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 px-1">Breakdown</h3>
            
            @forelse ($fees ?? [] as $fee)
                <div class="bg-white dark:bg-slate-800 px-4 py-3 flex justify-between rounded-lg shadow-sm border border-sky-100 dark:border-slate-700">
                    <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $fee->type ?? 'Tuition' }}</p>
                    <p class="text-slate-900 dark:text-slate-100 font-semibold">₱{{ number_format($fee->amount ?? 0, 2) }}</p>
                </div>
            @empty
                <div class="text-sm text-gray-500 dark:text-gray-400 px-1 italic">
                    No breakdown records found.
                </div>
            @endforelse
        </div>

        {{-- due dates --}}
        <div class="flex flex-col space-y-3">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 px-1">Due Dates</h3>
            
            @forelse ($dueDates ?? [] as $date)
                <div class="bg-white dark:bg-slate-800 px-4 py-3 flex justify-between rounded-lg shadow-sm border border-sky-100 dark:border-slate-700">
                    <p class="text-slate-600 dark:text-slate-300 font-medium">{{ $date->formatted_date ?? 'N/A' }}</p>
                    <p class="text-slate-900 dark:text-slate-100 font-semibold">₱{{ number_format($date->amount ?? 0, 2) }}</p>
                </div>
            @empty
                <div class="text-sm text-gray-500 dark:text-gray-400 px-1 italic">
                    No upcoming due dates.
                </div>
            @endforelse
        </div>

        {{-- transaction history --}}
        <div class="flex flex-col space-y-3">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 px-1">Transaction History</h3>
            
            @forelse ($transactions ?? [] as $transaction)
                <div class="bg-white dark:bg-slate-800 px-4 py-3 flex justify-between items-center rounded-lg shadow-sm border border-sky-100 dark:border-slate-700 gap-4">
                    <div class="flex flex-col">
                        <p class="text-slate-900 dark:text-slate-100 font-medium">{{ $transaction->type ?? 'Payment' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $transaction->date ?? 'Date unavailable' }}</p>
                    </div>
                    <p class="text-slate-900 dark:text-slate-100 font-semibold">₱{{ number_format($transaction->amount ?? 0, 2) }}</p>
                </div>
            @empty
                <div class="text-sm text-gray-500 dark:text-gray-400 px-1 italic">
                    No recent transactions.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>