<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth scroll-pt-20">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GSCAB School Management System</title>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex flex-col dark:text-neutral-100">

  <header class="w-full bg-blue-900 dark:bg-slate-800 py-4 px-8 flex flex-wrap justify-between items-center text-white sticky top-0 z-50">

      {{-- logo --}}
      <div class="flex items-center order-1">
        <a href="/">
          <img src="images/gscab-logo.svg" alt="gscab logo" class="w-8 h-8 mr-4" title="GSCAB Logo">
        </a>
        <h1 class="font-serif tracking-tight text-lg">GSCAB</h1>
      </div>

      {{-- navigation --}}
      <nav id="menu" class="hidden w-full md:flex md:w-auto mt-4 md:mt-0 order-3 md:order-2 md:ml-auto md:mr-8 transition-all duration-300">
        <ul class="flex flex-col md:flex-row w-full gap-2 md:gap-6 items-center justify-center tracking-wider text-base pb-4 md:pb-0">
          <x-guest.nav-link route="home" label="Home" />
          <x-guest.nav-link route="faqs" label="FAQs" />
          <x-guest.nav-link route="news" label="News" />
          {{-- <x-guest.nav-link route="contact" label="Contact" /> --}}
          <x-guest.nav-link route="auth.enroll" label="Enroll" />
        </ul>
      </nav>

      <div class="flex items-center gap-3 order-2 md:order-3">

        {{-- login btn --}}
        <a href="{{ route('auth.login') }}" class="bg-white text-blue-900 font-semibold py-1 px-5 rounded-full hover:bg-neutral-200 transition-colors text-sm md:text-base">
          Log in
        </a>

        {{-- burger btn --}}
        <button id="menu-btn" class="block md:hidden focus:outline-none ml-2">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>

  </header>

  <main {{ $attributes->merge(['class' => 'grow dark:bg-slate-900 min-h-screen']) }}>
    {{ $slot }}
  </main>
  
  <x-footer/>
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