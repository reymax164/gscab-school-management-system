<div {{ $attributes->merge(['class' => 'flex flex-col bg-white dark:bg-neutral-800 rounded-md overflow-hidden border border-neutral-400 dark:border-neutral-700 shadow-sm hover:shadow-md']) }}>
  
  {{-- Blue Header for Contrast --}}
  <div class="flex items-center shrink-0 mb-2 bg-blue-900 text-white justify-center font-semibold p-2">
    <p class="text-md md:text-lg md:truncate">
      {{ $month }}
    </p>
  </div>

  {{-- Calendar Body --}}
  <div class="flex-1 px-3 pb-4">
    <div class="grid grid-cols-7 gap-1 text-center text-xs sm:text-sm">
      
      <!-- Day Headers -->
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">Su</div>
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">Mo</div>
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">Tu</div>
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">We</div>
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">Th</div>
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">Fr</div>
      <div class="font-medium text-gray-500 dark:text-gray-400 pb-1">Sa</div>

      <!-- Blank cells for days before the 1st of the month -->
      @for ($i = 0; $i < $blankDays; $i++)
        <div class="p-1 sm:p-2"></div>
      @endfor

      <!-- Actual Calendar Days -->
      @for ($i = 1; $i <= $daysInMonth; $i++)
        @php
            $isToday = $isCurrentMonth && $i === $today;
        @endphp
        
        <div class="p-1 sm:p-2 rounded-md cursor-pointer transition-colors flex items-center justify-center
            {{ $isToday ? 'bg-blue-900 text-white font-bold shadow-sm' : 'text-gray-800 dark:text-neutral-100 hover:bg-gray-100 dark:hover:bg-neutral-700' }}">
            {{ $i }}
        </div>
      @endfor

    </div>
  </div>
</div>