<x-layouts.app title="Admin | Dashboard" header="Admin Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">
    
  <x-widgets.number
    title="Total Students"
    footer="View Students"
    :num="$studentCount"
  />

  <x-widgets.number
    title="Total Staff"
    footer="View Staffs"
    :num="$staffCount"
  />

  <x-widgets.number
    title="Recent Feedbacks"
    footer="View Feedbacks"
  />
</x-layouts.app>