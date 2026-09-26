<x-layouts.app title="Teacher | Student List" header="Student List"
  class="p-4 md:p-6 flex flex-col">

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">

    <div class="flex flex-wrap gap-x-10 gap-y-3">
      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Grade</span>
        <span class="font-semibold text-lg text-blue-900">{{ $section->grade_level === 'Kinder' ? 'Kindergarten' : 'Grade '.$section->grade_level }}</span>
      </div>

      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">S.Y.</span>
        <span class="font-semibold text-lg text-blue-900">{{ $section->school_year }}</span>
      </div>

      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Room</span>
        <span class="font-semibold text-lg text-blue-900">{{ $section->classroom->name ?? 'N/A' }}</span>
      </div>

      <div class="flex flex-col">
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Subject</span>
        <span class="font-semibold text-lg text-blue-900">
          @if ($subjects->isNotEmpty())
            {{ $subjects->implode(', ') }}
          @elseif ($isAdviser)
            Adviser
          @else
            N/A
          @endif
        </span>
      </div>
    </div>

    <a href="{{ route('teacher.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-md transition-colors shrink-0">Back</a>

  </div>

  <x-table :headers="['LRN', 'Full Name', 'Mother', 'Father', 'Mother Contact', 'Father Contact']">
    @forelse ($students as $student)
      @php
        $mother = $student->enrollment?->studentProfile?->mother_details;
        $father = $student->enrollment?->studentProfile?->father_details;
      @endphp
      <x-table.row>
        <x-table.cell bold>{{ $student->user->lrn ?? 'N/A' }}</x-table.cell>
        <x-table.cell>{{ $student->user->last_name ?? 'N/A' }}{{ isset($student->user) ? ', '.$student->user->first_name : '' }}</x-table.cell>
        <x-table.cell>{{ isset($mother['first_name'], $mother['maiden_last']) ? $mother['first_name'].' '.$mother['maiden_last'] : 'N/A' }}</x-table.cell>
        <x-table.cell>{{ isset($father['first_name'], $father['last_name']) ? $father['first_name'].' '.$father['last_name'] : 'N/A' }}</x-table.cell>
        <x-table.cell>{{ $mother['number'] ?? 'N/A' }}</x-table.cell>
        <x-table.cell>{{ $father['number'] ?? 'N/A' }}</x-table.cell>
      </x-table.row>
    @empty
      <x-table.row>
        <x-table.cell colspan="6" class="text-center text-neutral-500 py-8">No students enrolled in this section yet.</x-table.cell>
      </x-table.row>
    @endforelse
  </x-table>

</x-layouts.app>
