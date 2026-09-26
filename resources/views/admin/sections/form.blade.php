@php
    $isEdit = isset($classSchedule);
    
    // calculate default start year based on current month
    $defaultStartYear = now()->month >= 6 ? now()->year : now()->year - 1;
    
    // if editing, extract the first year from the existing "2026-2027" format
    if ($isEdit) {
        $defaultStartYear = explode('-',$classSchedule->school_year)[0];
    }
    
    // retain old input if validation fails
    $defaultStartYear = old('start_year',$defaultStartYear);

    $initialSlots = old('subject_schedules') ?? ($isEdit
        ? $classSchedule->subjectSchedules->map(fn ($s) => [
            'subject_id' => $s->subject_id,
            'teacher_id' => $s->teacher_id,
            'classroom_id' => $s->classroom_id,
            'days' => $s->days,
            'start_time' => substr($s->start_time, 0, 5),
            'end_time' => substr($s->end_time, 0, 5),
        ])->values()->all()
        : []);
@endphp

<form
    x-ref="scheduleForm"
    action="{{ $formAction }}"
    method="POST"
    @submit="submitForm($event)"
    x-data="{
      subjectOptions: @js($subjects),
      roomOptions: @js($classrooms),
      teacherOptions: @js($teachers),
      existingCounts: @js($existingCounts ?? []),
      adviserCounts: @js($adviserCounts ?? []),
      
      start_year: '{{ $defaultStartYear }}',
      dayOptions: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      slots: [],

      showModal: false,
      warnings: [],

      submitForm(e) {
          const form = e.target;
          const grade = form.querySelector('[name=\'grade_level\']').value;
          const sy = form.querySelector('[name=\'school_year\']').value;
          const adviser = form.querySelector('[name=\'adviser_id\']').value;

          if (!grade || !sy || !adviser) return; 

          this.warnings = [];

          const secCount = this.existingCounts[sy]?.[grade] || 0;
          if (secCount > 0) {
              const gradeText = grade === 'Kinder' ? 'Kindergarten' : 'Grade ' + grade;
              this.warnings.push(`There is already ${secCount} schedule(s) for ${gradeText} in S.Y. ${sy}.`);
          }

          const advCount = this.adviserCounts[sy]?.[adviser] || 0;
          if (advCount > 0) {
              const advName = this.optionLabel(this.teacherOptions, adviser) || 'This teacher';
              this.warnings.push(`${advName} is already advising ${advCount} class(es) in S.Y. ${sy}.`);
          }

          if (this.warnings.length > 0) {
              e.preventDefault();
              this.showModal = true;
          }
      },

      filteredOptions(options, query) {
        if (!query) return options;
        return options.filter(o => o.label.toLowerCase().includes(query.toLowerCase()));
      },

      optionLabel(options, id) {
        const found = options.find(o => o.id == id);
        return found ? found.label : '';
      },

      hydrateSlot(raw = {}) {
        return {
          subject_id: raw.subject_id ?? '',
          subjectQuery: this.optionLabel(this.subjectOptions, raw.subject_id ?? ''),
          subjectOpen: false,
          subjectPos: { top: 0, left: 0, width: 200 },
          classroom_id: raw.classroom_id ?? '',
          roomQuery: this.optionLabel(this.roomOptions, raw.classroom_id ?? ''),
          roomOpen: false,
          roomPos: { top: 0, left: 0, width: 200 },
          teacher_id: raw.teacher_id ?? '',
          teacherQuery: this.optionLabel(this.teacherOptions, raw.teacher_id ?? ''),
          teacherOpen: false,
          teacherPos: { top: 0, left: 0, width: 200 },
          days: raw.days ? raw.days.split(',') : [],
          daysOpen: false,
          daysPos: { top: 0, left: 0, width: 150 },
          start_time: raw.start_time ?? '',
          end_time: raw.end_time ?? '',
        };
      },

      addSlot() {
        this.slots.push(this.hydrateSlot());
      },

      removeSlot(index) {
        this.slots.splice(index, 1);
      },

      positionDropdown(el, slot, key) {
        const rect = el.getBoundingClientRect();
        slot[key] = { top: rect.bottom + 4, left: rect.left, width: rect.width };
      },

      init() {
        const old = @js($initialSlots);
        this.slots = old.length ? old.map(raw => this.hydrateSlot(raw)) : [this.hydrateSlot()];
      },
    }">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <!-- header: grade level, school year & adviser -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        
        <!-- left side -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 w-full lg:w-auto">
            
            <!-- Grade Level -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <x-form.select name="grade_level" label="Grade Level" required class="w-full sm:w-48">
                    <option value="" disabled selected>Select Grade</option>
                    <option value="Kinder" {{ old('grade_level', $isEdit ? $classSchedule->grade_level : '') == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('grade_level', $isEdit ? $classSchedule->grade_level : '') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                    @endfor
                </x-form.select>
            </div>

            <!-- S.Y. -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-neutral-700 dark:text-neutral-100 whitespace-nowrap">S.Y.</label>
                <input type="number" min="1990" name="start_year" x-model="start_year" required
                    class="w-24 border border-gray-300 rounded-md px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white" placeholder="YYYY">
                
                <span class="text-gray-600 font-medium whitespace-nowrap" x-text="start_year ? `- ${parseInt(start_year) + 1}` : '- YYYY'"></span>
                
                <input type="hidden" name="school_year" :value="start_year ? `${start_year}-${parseInt(start_year) + 1}` : ''">
            </div>
        </div>

        <!-- right side -->
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <x-form.select name="adviser_id" label="Adviser" required class="w-full sm:w-64">
                <option value="" disabled selected>Select Adviser</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher['id'] }}" {{ old('adviser_id', $isEdit ? $classSchedule->adviser_id : '') == $teacher['id'] ? 'selected' : '' }}>{{$teacher['label'] }}</option>
                @endforeach
            </x-form.select>
        </div>
        
    </div>

    <div class="rounded-md border border-gray-300 overflow-hidden bg-white">

        <!-- standard window -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-blue-900 text-white font-semibold">
                    <tr>
                        <th scope="col" class="px-3 py-3 min-w-48">Subject</th>
                        <th scope="col" class="px-3 py-3 min-w-32">Day</th>
                        <th scope="col" class="px-3 py-3 min-w-44">Time</th>
                        <th scope="col" class="px-3 py-3 min-w-48">Room</th>
                        <th scope="col" class="px-3 py-3 min-w-48">Subject Teacher</th>
                        <th scope="col" class="px-3 py-3 w-12"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(slot, index) in slots" :key="index">
                        <tr>
                            <!-- subject -->
                            <td class="px-3 py-2 align-middle">
                                <x-form.searchable-select
                                    options="subjectOptions"
                                    idField="subject_id"
                                    queryField="subjectQuery"
                                    openField="subjectOpen"
                                    posField="subjectPos"
                                    inputName="`subject_schedules[${index}][subject_id]`"
                                    placeholder="Search subject..."
                                />
                            </td>

                            <!-- days -->
                            <td class="px-3 py-2 align-middle">
                                <div class="relative" @click.away="slot.daysOpen = false">
                                    <button type="button" @click="slot.daysOpen = !slot.daysOpen; positionDropdown($el, slot, 'daysPos')"
                                        class="w-full text-left bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <span x-text="slot.days.length ? slot.days.join(', ') : 'Select days'"></span>
                                    </button>
                                    <input type="hidden" :name="`subject_schedules[${index}][days]`" :value="slot.days.join(',')">

                                    <template x-teleport="body">
                                        <div x-show="slot.daysOpen" x-cloak
                                            :style="`top:${slot.daysPos.top}px; left:${slot.daysPos.left}px; width:${slot.daysPos.width}px;`"
                                            class="fixed z-50 bg-white border border-gray-300 rounded-md shadow-lg p-2 flex flex-col gap-1">
                                            <template x-for="day in dayOptions" :key="day">
                                                <label class="flex items-center gap-2 text-sm px-1 py-1 hover:bg-gray-50 rounded cursor-pointer">
                                                    <input type="checkbox" :checked="slot.days.includes(day)"
                                                        @change="$event.target.checked ? slot.days.push(day) : slot.days.splice(slot.days.indexOf(day), 1)">
                                                    <span x-text="day"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </td>

                            <!-- time -->
                            <td class="px-3 py-2 align-middle">
                                <div class="flex items-center gap-1">
                                    <input type="time" x-model="slot.start_time" :name="`subject_schedules[${index}][start_time]`" required
                                        class="bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-gray-400">-</span>
                                    <input type="time" x-model="slot.end_time" :name="`subject_schedules[${index}][end_time]`" required
                                        class="bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </td>

                            <!-- room -->
                            <td class="px-3 py-2 align-middle">
                                <x-form.searchable-select
                                    options="roomOptions"
                                    idField="classroom_id"
                                    queryField="roomQuery"
                                    openField="roomOpen"
                                    posField="roomPos"
                                    inputName="`subject_schedules[${index}][classroom_id]`"
                                    placeholder="Search room..."
                                />
                            </td>

                            <!-- subject teacher -->
                            <td class="px-3 py-2 align-middle">
                                <x-form.searchable-select
                                    options="teacherOptions"
                                    idField="teacher_id"
                                    queryField="teacherQuery"
                                    openField="teacherOpen"
                                    posField="teacherPos"
                                    inputName="`subject_schedules[${index}][teacher_id]`"
                                    placeholder="Search teacher..."
                                />
                            </td>

                            <td class="px-3 py-2 align-middle text-center">
                                <button type="button" @click="removeSlot(index)" class="text-gray-400 hover:text-red-600 transition-colors" title="Remove time slot">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- smaller window -->
        <div class="block md:hidden divide-y divide-gray-200">
            <template x-for="(slot, index) in slots" :key="index">
                <div class="p-4 space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="font-bold text-blue-900" x-text="`Slot ${index + 1}`"></span>
                        <button type="button" @click="removeSlot(index)" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Remove
                        </button>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Subject</label>
                        <x-form.searchable-select
                            options="subjectOptions"
                            idField="subject_id"
                            queryField="subjectQuery"
                            openField="subjectOpen"
                            posField="subjectPos"
                            inputName="`subject_schedules[${index}][subject_id]`"
                            placeholder="Search subject..."
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Days</label>
                            <div class="relative" @click.away="slot.daysOpen = false">
                                <button type="button" @click="slot.daysOpen = !slot.daysOpen; positionDropdown($el, slot, 'daysPos')"
                                    class="w-full text-left bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <span class="truncate block" x-text="slot.days.length ? slot.days.join(', ') : 'Select days'"></span>
                                </button>
                                <input type="hidden" :name="`subject_schedules[${index}][days]`" :value="slot.days.join(',')">
                                
                                <template x-teleport="body">
                                    <div x-show="slot.daysOpen" x-cloak
                                        :style="`top:${slot.daysPos.top}px; left:${slot.daysPos.left}px; width:${slot.daysPos.width}px;`"
                                        class="fixed z-50 bg-white border border-gray-300 rounded-md shadow-lg p-2 flex flex-col gap-1">
                                        <template x-for="day in dayOptions" :key="day">
                                            <label class="flex items-center gap-2 text-sm px-1 py-1 hover:bg-gray-50 rounded cursor-pointer">
                                                <input type="checkbox" :checked="slot.days.includes(day)"
                                                    @change="$event.target.checked ? slot.days.push(day) : slot.days.splice(slot.days.indexOf(day), 1)">
                                                <span x-text="day"></span>
                                            </label>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Time</label>
                            <div class="flex flex-col gap-1">
                                <input type="time" x-model="slot.start_time" :name="`subject_schedules[${index}][start_time]`" required
                                    class="bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                                <input type="time" x-model="slot.end_time" :name="`subject_schedules[${index}][end_time]`" required
                                    class="bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Room</label>
                        <x-form.searchable-select
                            options="roomOptions"
                            idField="classroom_id"
                            queryField="roomQuery"
                            openField="roomOpen"
                            posField="roomPos"
                            inputName="`subject_schedules[${index}][classroom_id]`"
                            placeholder="Search room..."
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Teacher</label>
                        <x-form.searchable-select
                            options="teacherOptions"
                            idField="teacher_id"
                            queryField="teacherQuery"
                            openField="teacherOpen"
                            posField="teacherPos"
                            inputName="`subject_schedules[${index}][teacher_id]`"
                            placeholder="Search teacher..."
                        />
                    </div>
                </div>
            </template>
        </div>

        <!-- empty state -->
        <div x-show="slots.length === 0" class="text-center text-gray-500 py-8 px-4 border-b border-gray-200">
            No time slots yet. Click "Add Time Slot" below to begin.
        </div>

        <!-- centralized add time slot button -->
        <div class="bg-gray-50 px-4 py-4 border-t border-gray-200 flex justify-center">
            <button type="button" @click="addSlot()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-900 bg-blue-100 hover:bg-blue-200 shadow-sm transition-colors px-6 py-2.5 rounded-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Time Slot
            </button>
        </div>

        <!-- actions (cancel & save) -->
        <div class="bg-white flex justify-end gap-3 px-4 py-4 border-t border-gray-200">
            <a href="{{ route('admin.sections.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-md transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-blue-900 hover:bg-blue-900/90 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                Save
            </button>
        </div>
    </div>

    <!-- warning modal -->
    <template x-teleport="body">
        <div x-show="showModal" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showModal"
                 x-transition.opacity.duration.300ms
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showModal"
                         @click.away="showModal = false"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Heads Up</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-3">Please review the following before proceeding:</p>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-yellow-800 bg-yellow-50 p-3 rounded-md border border-yellow-200">
                                            <template x-for="(warning, index) in warnings" :key="index">
                                                <li x-text="warning"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" @click="$refs.scheduleForm.submit()" class="inline-flex w-full justify-center rounded-md bg-blue-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 sm:ml-3 sm:w-auto">Proceed Anyway</button>
                            <button type="button" @click="showModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</form>