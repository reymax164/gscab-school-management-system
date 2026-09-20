<x-layouts.app title="Teacher | Schedule" header="Class Schedule"
    class="p-4 md:p-6 flex flex-col">

  {{-- header and filters --}}
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <p class="font-semibold text-lg text-neutral-800 dark:text-neutral-100">
          {{ $schedules->count() }} {{ $schedules->count() === 1 ? 'Class' : 'Classes' }}
      </p>

      <div>
          <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-4">

              {{-- day filter --}}
              <x-form.select name="day" label="Day" onchange="this.form.submit()">
                  <option value="Today" {{ $activeDay == 'Today' ? 'selected' : '' }}>Today</option>
                  <option value="Monday" {{ $activeDay == 'Monday' ? 'selected' : '' }}>Monday</option>
                  <option value="Tuesday" {{ $activeDay == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                  <option value="Wednesday" {{ $activeDay == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                  <option value="Thursday" {{ $activeDay == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                  <option value="Friday" {{ $activeDay == 'Friday' ? 'selected' : '' }}>Friday</option>
                  <option value="Saturday" {{ $activeDay == 'Saturday' ? 'selected' : '' }}>Saturday</option>
              </x-form.select>

              {{-- year filter --}}
              <x-form.select name="sy" label="S.Y." onchange="this.form.submit()">
                  @foreach ($academicYears as $year)
                      <option value="{{ $year }}" {{ $activeSy == $year ? 'selected' : '' }}>
                          {{ $year }}
                      </option>
                  @endforeach
              </x-form.select>

              <noscript>
                  <button type="submit" class="px-3 py-1 bg-blue-900 text-white rounded-md text-sm">Filter</button>
              </noscript>
          </form>
      </div>
  </div>

  {{-- schedule table --}}
  <x-table :headers="['Subject', 'Grade & Section', 'Day', 'Time', 'Room']">
      @forelse($schedules as $schedule)
          <x-table.row>
              <x-table.cell bold>{{ $schedule->subject }}</x-table.cell>
              <x-table.cell>{{ $schedule->grade }}</x-table.cell>
              <x-table.cell>{{ $schedule->day }}</x-table.cell>
              <x-table.cell>{{ $schedule->time }}</x-table.cell>
              <x-table.cell>{{ $schedule->room }}</x-table.cell>
          </x-table.row>
      @empty
          <x-table.row>
              <x-table.cell colspan="5" class="text-center text-neutral-500 py-8">
                  @if($activeDay === 'Today' && now()->format('D') === 'Sun')
                      Enjoy your Sunday! No classes scheduled today.
                  @else
                      No classes scheduled for this day/school year.
                  @endif
              </x-table.cell>
          </x-table.row>
      @endforelse
  </x-table>
</x-layouts.app>