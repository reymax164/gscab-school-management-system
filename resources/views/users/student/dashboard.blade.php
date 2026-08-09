<x-layouts.app title="Student | Dashboard" header="Student Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">

  <div class=" border-l-8 border-l-blue-900 rounded-md p-4 md:px-6 col-span-2 bg-white shadow-md hover:shadow-lg flex overflow-hidden flex-col md:flex-row truncate">
    <img src="" alt="" class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-neutral-200 mr-6 self-center mb-6 md:mb-0">
    <div class="flex flex-col flex-1">
      <p class="text-sm md:text-base">Welcome Back,</p>
      <p class="font-semibold text-base md:text-xl">Nashley Cedrick A. Almazan</p>
      <p class="text-sm mb-0 md:mb-6">Section</p>

      <button class="self-end mt-0 md:mt-3 px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors">Edit</button>
    </div>
  </div>

  <x-widgets.number
    title="Balance"
    num="₱0.00"
  />

<x-widgets.blank class="col-start-1 row-start-3 md:row-start-1 md:col-start-3" title="Calendar"/>

  <x-widgets.scrollable
    title="Today's Classes"
    footer="View Schedule"
    empty="No Classes Today"
  />

  <x-widgets.scrollable
    title="Latest Grades"
    footer="View Grades"
    empty="No Recent Grades"
  />
   
  <x-widgets.blank class="col-span-2 md:col-span-3 flex flex-col h-64" title="Recent Announcements">
    <ul class="flex-1 min-h-0 border border-gray-300 rounded-md overflow-y-auto divide-y divide-gray-200">
    
      {{-- @forelse($announcements as $announcement)
        <x-announcement-item 
          :title="$announcement->title" 
          :date="$announcement->created_at->format('M d, Y')" 
          :url="route('announcements.show', $announcement)"
        />
      @empty
        <li class="p-4 text-center text-xs text-gray-400 italic">
          No recent announcements.</li>
      @endforelse --}}
   
    </ul>
    
    <button class="self-end mt-3 px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors">
      View All
    </button>
  </x-widgets.blank>
</x-layouts.app>