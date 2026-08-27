@php
    $isEdit = isset($classSchedule);

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
    action="{{ $formAction }}"
    method="POST"
    x-data="{
      subjectOptions: @js($subjects),
      roomOptions: @js($classrooms),
      teacherOptions: @js($teachers),
      dayOptions: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      slots: [],

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

      // positions a teleported dropdown panel under its anchor input so it never gets clipped
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

    <!-- header: grade level, school year & adviser (plain, no background) -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-end gap-3">
            <x-form.select name="grade_level" label="Grade Level" required class="w-40">
                <option value="">Select Grade</option>
                <option value="Kinder" {{ old('grade_level', $isEdit ? $classSchedule->grade_level : '') == 'Kinder' ? 'selected' : '' }}>Kindergarten</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('grade_level', $isEdit ? $classSchedule->grade_level : '') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                @endfor
            </x-form.select>

            <x-form.input name="academic_year" label="" required placeholder="S.Y." value="{{ old('academic_year', $isEdit ? $classSchedule->academic_year : '') }}" class="w-36" />
        </div>

        <x-form.select name="adviser_id" label="Adviser" required class="w-48">
            <option value="">Select Adviser</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher['id'] }}" {{ old('adviser_id', $isEdit ? $classSchedule->adviser_id : '') == $teacher['id'] ? 'selected' : '' }}>{{ $teacher['label'] }}</option>
            @endforeach
        </x-form.select>
    </div>

    <div class="rounded-md border border-gray-300 overflow-hidden">

        <!-- table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-blue-900 text-white font-semibold">
                    <tr>
                        <th scope="col" class="px-3 py-3 min-w-48">Subject</th>
                        <th scope="col" class="px-3 py-3 min-w-32">Day</th>
                        <th scope="col" class="px-3 py-3 min-w-44">Time</th>
                        <th scope="col" class="px-3 py-3 min-w-48">Room</th>
                        <th scope="col" class="px-3 py-3 min-w-48">Subject Teacher</th>
                        <th scope="col" class="px-3 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(slot, index) in slots" :key="index">
                        <tr>
                            <!-- subject -->
                            <td class="px-3 py-2 align-top">
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
                            <td class="px-3 py-2 align-top">
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
                            <td class="px-3 py-2 align-top">
                                <div class="flex items-center gap-1">
                                    <input type="time" x-model="slot.start_time" :name="`subject_schedules[${index}][start_time]`" required
                                        class="bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-gray-400">-</span>
                                    <input type="time" x-model="slot.end_time" :name="`subject_schedules[${index}][end_time]`" required
                                        class="bg-white border border-neutral-300 rounded-md px-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </td>

                            <!-- room -->
                            <td class="px-3 py-2 align-top">
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
                            <td class="px-3 py-2 align-top">
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

                            <!-- remove row -->
                            <td class="px-3 py-2 align-top text-right">
                                <button type="button" @click="removeSlot(index)" class="text-gray-400 hover:text-red-600 transition-colors" title="Remove time slot">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="slots.length === 0">
                        <td colspan="6" class="text-center text-gray-400 py-6">No time slots yet. Click "Add Time Slot" below to begin.</td>
                    </tr>

                    <!-- always the bottom-most row -->
                    <tr>
                        <td colspan="6" class="bg-gray-50 px-4 py-3">
                            <button type="button" @click="addSlot()" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-900 hover:text-blue-900/80 transition-colors">
                                <x-heroicon-o-plus class="w-4 h-4" />
                                Add Time Slot
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- actions -->
        <div class="bg-white flex justify-end gap-3 px-4 py-4 border-t border-gray-200">
            <a href="{{ route('admin.class-schedules.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-md transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-blue-900 hover:bg-blue-900/90 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">
                Save
            </button>
        </div>

    </div>
</form>
