<x-layouts.app title="Teacher | Student List"
               header="Student List"
               class="flex flex-col p-4 md:p-6">

    {{-- S.Y. Filter --}}
    <form action="{{ url()->current() }}" method="GET" class="mb-6 max-w-xs">
      <x-form.select name="sy" label="S.Y." onchange="this.form.submit()">
        @for ($i = 2020; $i <= now()->year; $i++)
            @php $syOption = "{$i}-" . ($i + 1); @endphp
            <option value="{{ $syOption }}" @selected($selectedSy === $syOption)>
              {{ $syOption }}
            </option>
        @endfor
      </x-form.select>
    </form>

    <h2 class="mb-3 text-lg font-semibold text-neutral-800">
      Assigned Classes for S.Y. {{ $selectedSy }}
    </h2>

    <div class="w-full grow rounded-md border border-neutral-200 bg-neutral-50 p-4">
      <ul class="space-y-3">
        @forelse ($classes as $class)
          <li>
            <div class="flex items-center justify-between rounded-md bg-white p-4 shadow-sm transition hover:shadow-md">
              <div>

                <p class="font-medium text-neutral-900">
                  Grade {{ $class->grade_level ?? 'N/A' }}
                  @if(!empty($class->section_name))
                      - {{ $class->section_name }}
                  @endif
                </p>

                <p class="text-sm text-neutral-500">
                  {{ $class->subject->name ?? 'No Subject' }} &bull; {{ $class->students_count ?? $class->students?->count() ?? 0 }} Students
                </p>

              </div>
                
                @php
                  $showUrl = Route::has('view-student-list.show') && isset($class->id)
                    ? route('view-student-list.show', $class->id) 
                    : '#';
                @endphp

                <a href="{{ $showUrl }}" 
                  class="inline-flex items-center gap-2 rounded-md bg-blue-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-900/90">
                  <span>View Students</span>
                  
                  <svg xmlns="http://www.w3.org/2000/svg" 
                      width="16" 
                      height="16" 
                      viewBox="0 0 24 24" 
                      fill="none" 
                      stroke="currentColor" 
                      stroke-width="2" 
                      stroke-linecap="round" 
                      stroke-linejoin="round" 
                      class="h-4 w-4 shrink-0">
                    <path d="m9 18 6-6-6-6"/>
                  </svg>
                </a>
            </div>
          </li>
        @empty
          <li class="rounded-md bg-white p-8 text-center text-neutral-500 shadow-sm">
              No classes or students found for S.Y. {{ $selectedSy }}.
          </li>
        @endforelse
      </ul>
    </div>

</x-layouts.app>