<x-layouts.app title="Student | Dashboard" header="Student Dashboard"
               class="grid grid-cols-2 md:grid-cols-3 gap-4 content-start p-4 md:p-6">
  @php $user = auth()->user(); @endphp

  {{-- Profile Section --}}
  <div class="
    bg-white dark:bg-neutral-800
    flex overflow-hidden flex-col md:flex-row truncate
    border border-neutral-400 dark:border dark:border-neutral-700
    rounded-md p-4 md:px-6 col-span-2
    shadow-sm hover:shadow-md">


    @if($user?->profile_photo_url)
      <img 
        src="{{ $user->profile_photo_url }}" 
        alt="{{ $user->name ?? 'Profile' }}'s Avatar" 
        class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-full bg-neutral-200 mr-6 self-center mb-6 md:mb-0 shrink-0"
      />
    @else
      <x-heroicon-s-user-circle 
        class="w-24 h-24 md:w-32 md:h-32 text-gray-400 dark:text-neutral-500 mr-6 self-center mb-6 md:mb-0 shrink-0" 
      />
    @endif

    <div class="flex flex-col flex-1">
      <p class="text-sm md:text-base text-blue-900 dark:text-blue-400">Welcome Back,</p>
      
      {{-- name --}}
      <p class="font-semibold text-base md:text-xl dark:text-white">
          {{ $user?->first_name ?? 'User' }} 
          {{ $user?->last_name ?? '' }} 
          {{ $user?->suffix ?? '' }}
      </p>
      
      <p class="text-sm mb-0 md:mb-6 dark:text-neutral-300">
          Grade {{ $user?->student?->grade_level ?? '' }}
      </p>

      <button class="self-end mt-0 md:mt-3 px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors dark:hover:bg-gray-100/10 dark:text-neutral-200 dark:border-neutral-600">
        Edit
      </button>
    </div>
  </div>

  <x-widgets.number
    title="Balance"
    num="₱0.00"
    :href="route('student.balance')"
  />

  <x-widgets.blank
    class="row-start-4 col-start-1 col-end-3 md:row-start-1 md:col-start-3"
    title="Calendar"
    header=true
  />

  <x-widgets.scrollable
    title="Today's Classes"
    empty="No Classes Today"
    footer="View Schedule"
    :href="route('student.schedule')"
  />

  <x-widgets.scrollable
    title="Latest Grades"
    empty="No Recent Grades"
    footer="View Grades"
    :href="route('student.grades')"
  />
   
  <x-widgets.blank
    class="row-start-4 col-start-1 col-end-3 md:row-start-1 md:col-start-3"
    title="Calendar"
    :header="true" 
  />

  <x-widgets.scrollable
    title="Today's Classes"
    footer="View Schedule"
    :href="route('student.schedule')"
    empty="No Classes Today"
  />
   
  <x-widgets.blank
    class="col-span-2 md:col-span-3 h-64
           dark:bg-neutral-800 dark:text-neutral-100 dark:border-neutral-700 dark:border"
    title="Recent Announcements">

    <div class="flex flex-col h-full">
      
      <ul class="flex-1 min-h-0 border border-gray-300 rounded-md overflow-y-auto divide-y divide-gray-200 dark:border-neutral-500">
        @forelse($announcements ?? [] as $announcement)
          <li class="p-3 hover:bg-slate-50 transition-colors dark:hover:bg-neutral-700">
            <a href="{{ route('announcements.show', $announcement) }}" class="flex flex-col gap-1">
              <span class="font-medium text-blue-900 dark:text-blue-400">{{ $announcement->title }}</span>
              <span class="text-xs text-gray-500 dark:text-gray-400">{{ $announcement->created_at->format('M d, Y') }}</span>
            </a>
          </li>
        @empty
          <li class="p-4 text-center text-xs text-gray-400 italic">
            No recent announcements.
          </li>
        @endforelse
      </ul>
      
      <button class="shrink-0 self-end mt-3 px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors dark:hover:bg-gray-100/10">
        View All
      </button>

    </div>
  </x-widgets.blank>
</x-layouts.app>