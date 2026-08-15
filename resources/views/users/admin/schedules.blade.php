<x-layouts.app title="Admin | Schedules" header="Manage Schedules"
  class="p-4 md:p-6 flex flex-col">

  <div class="w-full flex justify-between flex-row items-center">
    <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row gap-4 mb-6 max-w-md grow">

      <x-form.select name="sort" id="" label="Sort by" class="w-52">
        <option value="">Grade Level (Acending)</option>
        <option value="">Grade Level (Descending)</option>
        <option value="">Last Added/Edited</option>
        <option value="">Room Number</option>
        <option value="">Class Adviser</option>
      </x-form.select>

      <x-form.select label="S.Y." name="sy" class="" />

    </form>

    <div>
      <a href="{{-- {{ route('schedules.create') }} --}}" class="bg-blue-900 hover:bg-blue-900/90 text-white text-sm px-3 py-2 rounded-sm inline-flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Schedule
      </a>
    </div>

  </div>

  <div class="h-full grow px-4 py-6">
  <ul class="flex flex-col gap-4">
    @forelse ($schedules ?? [] as $schedule)
      <li>
        <div class="bg-white w-full rounded-md border border-gray-400 shadow-sm hover:shadow-md p-4 md:px-6 md:py-4 flex flex-col md:flex-row justify-between items-center text-blue-900 transition-shadow duration-200">
          
          <!-- schedule grid -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-12 w-full md:w-3/4 mb-4 md:mb-0">
            
            <!-- grade & S.Y. -->
            <div class="flex flex-col">
              <span class="font-semibold text-sm">Grade {{ $schedule->grade_level }}</span>
              <span class="text-sm mt-1">S.Y. {{ $schedule->school_year }}</span>
            </div>

            <!-- room -->
            <div class="flex flex-col">
              <span class="font-semibold text-sm">Room</span>
              <span class="text-sm mt-1">{{ $schedule->room->name ?? 'N/A' }}</span>
            </div>

            <!-- adviser -->
            <div class="flex flex-col">
              <span class="font-semibold text-sm">Adviser</span>
              <span class="text-sm mt-1">{{ $schedule->adviser->name ?? 'N/A' }}</span>
            </div>

          </div>

          <!-- action links -->
          <div class="flex items-center gap-2 w-full md:w-auto justify-end font-medium text-white">
            <a href="{{-- {{ route('schedules.show', $schedule) }} --}}" class="text-xs w-16 text-center hover:underline bg-green-600 rounded-sm px-3 py-2">View</a>
            <a href="{{-- {{ route('schedules.edit', $schedule) }} --}}" class="text-xs w-16 text-center hover:underline bg-yellow-600 rounded-sm px-3 py-2">Edit</a>
            
            <form action="{{-- {{ route('schedules.destroy', $schedule) }} --}}" method="POST" class="inline m-0 p-0">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-xs w-16 text-center hover:underline bg-red-600 rounded-sm px-3 py-2" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
          </div>

        </div>
      </li>
    @empty
      <!-- empty -->
      <li class="text-center text-gray-500 py-10 bg-gray-50 rounded-md border border-dashed border-gray-300">
        No schedules found.
      </li>
    @endforelse
  </ul>
</div>

</x-layouts.app>