{{-- nav bar of authenticaed users --}}
@php
    // gets user role
    $user_type = auth()->user()->user_type ?? 'registrar';
    
    // gets the nav links from config/navigation.php
    $links = config("navigation.{$user_type}", []);
@endphp

<div 
  id="sidebar-backdrop"
  onclick="toggleSidebar(false)" 
  class="fixed inset-0 bg-black/50 z-40 hidden md:hidden">
</div>

<nav 
  id="mobile-sidebar"
  aria-label="Sidebar Navigation"
  class="h-full w-56 bg-blue-900 dark:bg-slate-800 text-white fixed z-50 top-0 left-0 py-6 pl-4 pr-2 flex flex-col items-center gap-3 shadow-lg transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 overflow-y-auto">

  <button 
    onclick="toggleSidebar(false)" 
    type="button" 
    class="absolute top-4 right-4 md:hidden text-white focus:outline-none" 
    aria-label="Close sidebar">

        @svg('heroicon-s-x-mark', 'w-6 h-6 text-neutral-400')

  </button>

  <a href="{{ url('/') }}" class="mb-2 focus:outline-none shrink-0">
    <img 
      src="{{ asset('images/gscab-logo.webp') }}" 
      alt="GSCAB logo" 
      class="h-12 md:h-24 w-auto object-contain"
      fetchpriority="high"
      loading="eager"
      width="96" 
      height="96"
    />
  </a>

  <ul class="flex flex-col gap-2 w-full text-sm">

    {{-- loads links based on the user type --}}
    @foreach ($links as $link)
      <x-nav.link 
        :route="$link['route']" 
        :label="$link['label']" 
        :icon="$link['icon'] ?? null" 
      />
    @endforeach

    {{ $slot }}
  </ul>
</nav>