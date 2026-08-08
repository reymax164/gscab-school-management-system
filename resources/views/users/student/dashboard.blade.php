<x-layouts.app title="Student | Dashboard" header="Student Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">

  <div class=" border-l-8 border-l-blue-900  rounded-md px-4 py-6 col-span-2 bg-white shadow-md hover:shadow-lg flex overflow-hidden">
    <img src="" alt="" class="w-32 h-32 rounded-full bg-neutral-200 mr-6">
    <div class="flex flex-col flex-1">
      <p>Welcome Back,</p>
      <p class="font-semibold text-xl">Name</p>
      <p class="text-sm mb-6">Section</p>
      <a href="#" class="self-end mt-3 px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors">Edit</a>
    </div>
  </div>

  <x-widgets.number
    title="Balance"
    num="₱0.00"
  />

  <x-widgets.scrollable
    title="Today's Classes"
    footer="View Schedule"
    empty="No Classes Today"
  />

  <x-widgets.scrollable
    title="Latest Quarterly Grades"
    footer="View Grades"
    empty="No Recent Quarterly Grade"
  />
   
  <x-widgets.blank class="col-span-3 flex flex-col h-64" title="Recent Announcements">
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