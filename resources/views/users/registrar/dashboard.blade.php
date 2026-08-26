<x-layouts.app title="Registar | Dashboard" header="Registrar Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">
  
  <x-widgets.number
    title="Enrolled Students"
    footer="View Enrolled Students"
    :num="$enrolledCount"
    :href="route('registrar.enrolled.index')"
  />

  <x-widgets.number
    title="Pending Applications"
    footer="View Pending Applications"
    :num="$pendingCount"
    :href="route('registrar.applications.index')"
  />
</x-layouts.app>