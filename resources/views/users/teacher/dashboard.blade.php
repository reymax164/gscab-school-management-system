<x-layouts.app title="Teacher | Dashboard" header="Teacher Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">

  <x-widgets.number
    title="Total Students"
    footer="View Student List"
    :href="route('teacher.students')"
  />

  <x-widgets.number
    title="Pending Grades"
    footer="View Student Grades"
    :href="route('teacher.grades')"
  />

  <x-widgets.blank
    class="row-start-4 col-start-1 col-end-3 md:row-start-1 md:col-start-3"
    title="Calendar"
    :header="true"
  />

  <x-widgets.scrollable
    title="Today's Schedule"
    empty="No Classes for Today"
    footer="View Full Schedule"
    :href="route('teacher.schedule')"
  />

  <x-widgets.scrollable
    class="row-start-3 col-start-1 col-end-3 md:col-end-4 h-64"
    title="Scheduled Meetings"
    empty="No Meetings Scheduled"
    :header="true"
  />
</x-layouts.app>