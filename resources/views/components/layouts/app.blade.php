{{-- base layout for all user types --}}

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
      aria-label="Open sidebar"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

    <h1 class="text-blue-900 dark:text-blue-400 font-bold text-xl">{{ $header }}</h1>
  </div>
  
  <div class="flex items-center gap-2 sm:gap-3 shrink-0">

    <div class="text-right hidden sm:block">
      <strong class="block font-bold text-gray-900 dark:text-neutral-100">
        {{ auth()->user()?->name ?? 'User' }}
      </strong>
      <p class="text-sm text-gray-600 dark:text-neutral-300">
        {{ ucfirst(request('role') ?? auth()->user()?->role ?? 'user type') }}
      </p>
    </div>

    <!-- dropdown wrapper -->
    <div class="relative">
      <!-- profile button -->
      <button 
        id="profileDropdownBtn"
        onclick="toggleProfileDropdown()"
        type="button"
        class="flex items-center focus:outline-none rounded-full ring-2 ring-transparent hover:ring-gray-300 dark:hover:ring-neutral-600 transition-all"
        aria-expanded="false"
      >
        <img 
          src="{{ auth()->user()?->profile_photo_url ?? '' }}" 
          alt="Profile" 
          class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover bg-neutral-200 shrink-0"/>  
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
        
        <!-- logout placeholder -->
        <button 
          type="button" 
          class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors"
        >
          Logout
        </button>
      </div>
    </div>
  </div>

</header>

  <!-- navigation -->
  <x-nav.bar />

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