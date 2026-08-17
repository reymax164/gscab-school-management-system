{{-- nav bar of authenticaed users --}}
@php
    // gets user role
    $role = auth()->user()->role ?? 'registrar';
    
    // gets the nav links from config/navigation.php
    $links = config("navigation.{$role}", []);
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

    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>

  </button>

  <a href="{{ url('/') }}" class="mb-2 focus:outline-none shrink-0">
    <img src="{{ asset('images/gscab-logo.svg') }}" alt="GSCAB logo" class="h-12 md:h-24 w-auto object-contain" />
  </a>

  <ul class="flex flex-col gap-2 w-full">

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