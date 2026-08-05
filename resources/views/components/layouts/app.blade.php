{{-- base layout for all user types --}}

@props(['title' => 'Dashboard', 'header' => ''])

<!DOCTYPE html>

<html lang="en">
<head>
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-neutral-900 min-h-screen">

  <header class="flex justify-between items-center w-full py-4 px-4 sm:px-8 md:pl-64 top-0 z-10">

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

      <h1 class="text-blue-900 dark:text-neutral-100 font-bold text-xl">{{ $header }}</h1>
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

      <img 
        src="{{ auth()->user()?->profile_photo_url ?? '' }}" 
        alt="Profile" 
        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover bg-neutral-200 shrink-0"/>  
    </div>

  </header>

  <!-- navigation -->
  <x-nav.bar />

  <main class="min-h-screen md:ml-56 p-4 md:p-6 grow flex justify-between">
    {{ $slot }}
  </main>

  <x-footer class="md:ml-56" />


  <script>
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
  </script>

</body>
</body>
</html>