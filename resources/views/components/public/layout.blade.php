<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth scroll-pt-20">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GSCAB School Management System</title>

  @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex flex-col dark:text-neutral-100">

  <header class="w-full bg-blue-900 py-4 px-8 flex flex-wrap justify-between items-center text-white sticky top-0 z-50">

      <!-- logo -->
      <div class="flex items-center order-1">
        <a href="/">
          <img src="" alt="gscab logo" class="w-8 h-8 mr-4" title="GSCAB Logo">
        </a>
        <h1 class="font-serif tracking-tight text-lg">GSCAB</h1>
      </div>

      <!-- navigation -->
      <nav id="menu" class="hidden w-full md:flex md:w-auto mt-4 md:mt-0 order-3 md:order-2 md:ml-auto md:mr-8 transition-all duration-300">
        <ul class="flex flex-col md:flex-row w-full gap-2 md:gap-6 items-center justify-center tracking-wider text-base pb-4 md:pb-0">
          <x-public.nav-link href="/">Home</x-public-nav-link>
          <x-public.nav-link href="/news">News</x-public-nav-link>
          <x-public.nav-link href="/contact">Contact</x-public-nav-link>
          <x-public.nav-link href="/faqs">FAQs</x-public.nav-link>
          <x-public.nav-link href="/enroll">Enroll</x-public.nav-link>
        </ul>
      </nav>

      <div class="flex items-center gap-3 order-2 md:order-3">

        <!-- login btn -->
        <a href="/auth" class="bg-white text-blue-900 font-semibold py-1 px-5 rounded-full hover:bg-neutral-200 transition-colors text-sm md:text-base">
          Log in
        </a>

        <!-- burger btn -->
        <button id="menu-btn" class="block md:hidden focus:outline-none ml-2">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>

  </header>

  <main {{ $attributes->merge(['class' => 'grow dark:bg-slate-900']) }}>
    {{ $slot }}

      <div id="modal-overlay" class="hidden inset-0 w-screen h-screen bg-neutral-800/20 fixed z-60 flex justify-center items-center">
    
      <!-- modal -->
      <div class="relative bg-white p-8 rounded-md shadow-lg border-t-blue-800 border-t-4">

        <button id="close-modal-btn" class="absolute top-3 right-3 text-neutral-400 hover:text-neutral-600 transition-colors focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <p class="mb-4 text-black dark:text-white">Modal Content Here</p>
      </div>

    </div>
  </main>
  
  <footer class="bg-neutral-200 dark:bg-slate-950 h-14 text-center content-center">
    <p class="text-neutral-500 text-sm">&copy; Good Shepherd Christian Academy of Batangas. All Rights Reserved.</p>
  </footer>
</body>

  <script>
    // burger button
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('menu');

    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menu.classList.toggle('flex');
    });
  </script>

</html>