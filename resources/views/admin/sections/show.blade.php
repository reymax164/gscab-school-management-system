<x-layouts.app title="Admin | View Schedule" header="Class Schedule"
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
        <span class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Adviser</span>
        <span class="font-semibold text-lg text-blue-900">{{ $section->adviser->user->last_name ?? 'N/A' }}{{ isset($section->adviser->user) ? ', '.$section->adviser->user->first_name : '' }}</span>
      </div>
    </div>

    <div class="flex items-center gap-2 shrink-0">
      <a href="{{ route('admin.sections.edit', $section) }}" class="bg-yellow-600 hover:bg-yellow-600/90 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors">Edit</a>
      <a href="{{ route('admin.sections.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-md transition-colors">Back</a>
    </div>

  </div>

  <x-table :headers="['Subject', 'Day', 'Time', 'Room', 'Subject Teacher']">
    @forelse ($section->subjectSchedules as $subjectSchedule)
      <x-table.row>
        <x-table.cell bold>{{ $subjectSchedule->subject->title ?? 'N/A' }}</x-table.cell>
        <x-table.cell>{{ $subjectSchedule->days }}</x-table.cell>
        <x-table.cell>{{ \Illuminate\Support\Carbon::parse($subjectSchedule->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($subjectSchedule->end_time)->format('g:i A') }}</x-table.cell>
        <x-table.cell>{{ $subjectSchedule->classroom->name ?? 'N/A' }}</x-table.cell>
        <x-table.cell>{{ $subjectSchedule->teacher->user->last_name ?? 'N/A' }}{{ isset($subjectSchedule->teacher->user) ? ', '.$subjectSchedule->teacher->user->first_name : '' }}</x-table.cell>
      </x-table.row>
    @empty
      <x-table.row>
        <x-table.cell colspan="5" class="text-center text-neutral-500 py-8">No subjects scheduled yet.</x-table.cell>
      </x-table.row>
    @endforelse
  </x-table>

</x-layouts.app>
