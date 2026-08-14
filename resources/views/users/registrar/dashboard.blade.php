<x-layouts.app title="Registar | Dashboard" header="Registrar Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">
  
  <x-widgets.number
    title="Enrolled Students"
    subtitle="S.Y."
    footer="View Student Records"
  />

  <x-widgets.number
    title="Pending Enrollees"
    subtitle="for evaluation"
    footer="View"
  />
  <x-widgets.number
    title="Enrollees with"
    subtitle="Incomplete Documents"
  />
</x-layouts.app>