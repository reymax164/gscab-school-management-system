{{-- base layout for all user types --}}
@php $user = auth()->user(); @endphp
@props(['title' => 'Dashboard', 'header' => ''])

<!DOCTYPE html>

<html lang="en">
<head>
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- checks theme preference -->
    <script>
      if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    </script>
    <link rel="preload" href="{{ asset('images/gscab-logo.webp') }}" as="image" type="image/webp">
  
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-neutral-900 dark:text-neutral-100 min-h-screen">

  <header
    class="
      bg-gray-100/90 dark:bg-neutral-900/90 backdrop-blur-sm
      flex justify-between items-center
      w-full py-4 px-4 sm:px-8 md:pl-64
      top-0 left-0 right-0 sticky
      z-10">

    <div class="flex items-center gap-3">
      <button 
        onclick="toggleSidebar(true)" 
        type="button" 
        class="md:hidden text-blue-900 dark:text-neutral-100 focus:outline-none" 
        aria-label="Open sidebar">

        @svg('heroicon-s-bars-3', 'w-6 h-6 text-neutral-400 dark:neutral-700')

      </button>

      <h1 class="text-blue-900 dark:text-blue-400 font-bold text-xl">{{ $header }}</h1>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">

      <div class="text-right hidden sm:block">

        {{-- name --}}
        <strong class="block text-sm font-semibold text-gray-900 dark:text-neutral-100">
          {{ $user?->first_name ?? 'User' }} {{ $user?->last_name ?? '' }}
        </strong>

        {{-- user type --}}
        <p class="text-sm text-gray-600 dark:text-neutral-300">
            @if($user?->user_type === 'student')
                Grade {{ $user->student?->grade_level }}
            @else
                {{ ucfirst(request('user_type') ?? $user?->user_type ?? 'user type') }}
            @endif
        </p>
      </div>

      {{-- dropdown wrapper --}}
      <div class="relative">
        
        {{-- profile button --}}
        <button 
          id="profileDropdownBtn"
          onclick="toggleProfileDropdown()"
          type="button"

          class="flex items-center justify-center focus:outline-none rounded-full ring-2 ring-transparent hover:ring-gray-300 dark:hover:ring-neutral-600 transition-all w-9 h-9 sm:w-10 sm:h-10 shrink-0 overflow-hidden"
          aria-expanded="false"
        >
          @if($user?->profile_photo_url)

            <img 
              src="{{ $user->profile_photo_url }}" 
              alt="Profile" 
              class="w-full h-full object-cover bg-neutral-200"
            />

          @else
            <x-heroicon-s-user-circle class="w-full h-full text-gray-400 dark:text-neutral-500 scale-125" />
          @endif
        </button>

        <!-- profile dorpwdown -->
        <div 
          id="profileDropdownMenu"
          class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-md shadow-lg py-1 z-50"
        >
          <!-- dark mode toggle -->
          <button 
            type="button"
            onclick="toggleDarkMode()" 
            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors"
          >
            Toggle Dark Mode
          </button>
          
          <!-- logout button -->
          <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button
              type="submit"
              class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors"
            >
              Logout
            </a>
          </form>
        </div>
      </div>
    </div>

  </header>

  <!-- navigation -->
  <x-nav.bar/>

  <main
    {{ $attributes->merge(['class' => 'md:ml-56 min-h-screen grow'])}}>
    {{ $slot }}
  </main>

  <x-footer class="md:ml-56" />

  <script>
    // sidebar
    function toggleSidebar(open) {
      const sidebar = document.getElementById('mobile-sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      if (open) {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        backdrop.classList.add('hidden');
      }
    }

    // profile dropdown
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdownMenu');
        dropdown.classList.toggle('hidden');
    }

    // close dropdown on outside click
    document.addEventListener('click', function(event) {
        const button = document.getElementById('profileDropdownBtn');
        const dropdown = document.getElementById('profileDropdownMenu');
        
        if (button && dropdown) {
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        }
    });

    // toggle dark mode
    function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        
        if (document.documentElement.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    }
  </script>

</body>
</html>