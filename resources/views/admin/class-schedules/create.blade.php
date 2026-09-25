<x-layouts.app title="Admin | Create Schedule" header="Create Class Schedule"
  class="p-4 md:p-6 flex flex-col">

  @if ($errors->any())
    <div class="mb-4 rounded-md border border-red-300 bg-red-50 text-red-700 text-sm p-4">
      <p class="font-semibold mb-1">Please fix the following:</p>
      <ul class="list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @include('admin.class-schedules.form', [
      'formAction' => route('admin.class-schedules.store'),
      'formMethod' => 'POST',
  ])

</x-layouts.app>
