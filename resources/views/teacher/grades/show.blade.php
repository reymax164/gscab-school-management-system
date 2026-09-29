<x-layouts.app title="Teacher | Grades" header="Student Grades"
  class="p-4 md:p-6 flex flex-col">

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">

    <div class="flex flex-wrap gap-x-10 gap-y-3">
      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Grade</span>
        <span class="font-semibold text-lg text-blue-900">{{ $subjectSchedule->section->grade_level === 'Kinder' ? 'Kindergarten' : 'Grade '.$subjectSchedule->section->grade_level }}</span>
      </div>

      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">S.Y.</span>
        <span class="font-semibold text-lg text-blue-900">{{ $subjectSchedule->section->school_year }}</span>
      </div>

      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Room</span>
        <span class="font-semibold text-lg text-blue-900">{{ $subjectSchedule->section->classroom->name ?? 'N/A' }}</span>
      </div>

      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Subject</span>
        <span class="font-semibold text-lg text-blue-900">{{ $subjectSchedule->subject->title ?? 'N/A' }}</span>
      </div>
    </div>

    <a href="{{ route('teacher.grades.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-md transition-colors shrink-0">Back</a>

  </div>

  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-2">
    <div class="flex items-center gap-3 w-full xl:w-auto">
      <button type="button" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md font-medium shadow-sm transition w-full sm:w-auto text-sm">
        Download Template
      </button>
      <button type="button" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md font-medium shadow-sm transition w-full sm:w-auto text-sm">
        Upload Grades
      </button>
    </div>
  </div>

  <form method="POST" action="{{ route('teacher.grades.update', $subjectSchedule) }}">
    @csrf
    @method('PATCH')

    <x-table :headers="['LRN', 'Full Name', 'Q1', 'Q2', 'Q3', 'Q4']">
      @forelse ($students as $student)
        @php
          $gradesByQuarter = $student->grades->keyBy('quarter');
        @endphp
        <x-table.row>
          <x-table.cell bold>{{ $student->user->lrn ?? 'N/A' }}</x-table.cell>
          <x-table.cell>{{ $student->user->last_name ?? 'N/A' }}{{ isset($student->user) ? ', '.$student->user->first_name : '' }}</x-table.cell>
          @foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
            <x-table.cell>
              <input
                type="number"
                step="0.01"
                min="0"
                max="100"
                name="grades[{{ $student->id }}][{{ $quarter }}]"
                value="{{ old("grades.{$student->id}.{$quarter}", $gradesByQuarter[$quarter]->grade ?? '') }}"
                class="w-20 border border-gray-400 dark:border-neutral-600 rounded px-2 py-1 text-center bg-white dark:bg-neutral-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-all"
              >
            </x-table.cell>
          @endforeach
        </x-table.row>
      @empty
        <x-table.row>
          <x-table.cell colspan="6" class="text-center text-neutral-500 py-8">No students enrolled in this section yet.</x-table.cell>
        </x-table.row>
      @endforelse
    </x-table>

    @if ($students->isNotEmpty())
      <div class="flex justify-end mt-4">
        <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-8 py-1.5 rounded-md font-medium shadow-sm transition text-sm border-2 border-blue-900">
          Save
        </button>
      </div>
    @endif
  </form>

</x-layouts.app>