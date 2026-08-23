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
          <x-guest.nav-link route="auth.enroll" label="Enroll" />
        </ul>
      </nav>

      <div class="flex items-center gap-3 order-2 md:order-3">

        <!-- unauthenticaled login -->
        @guest
            <button 
              x-data 
              @click="$dispatch('open-login-modal')" 
              class="bg-white text-blue-900 font-semibold py-1 px-5 rounded-full hover:bg-neutral-200 transition-colors text-sm md:text-base cursor-pointer">
              Login
            </button>
        @endguest

        <!-- authenticated login -->
        @auth
            <a href="{{ url('/' . Auth::user()->user_type . '/dashboard') }}" 
              class="bg-white text-blue-900 font-semibold py-1 px-5 rounded-full hover:bg-neutral-200 transition-colors text-sm md:text-base cursor-pointer inline-block text-center border border-transparent">
              Login
            </a>
        @endauth

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

    <!-- login modal -->
    <div 
        x-data="{ isOpen: false }" 
        @open-login-modal.window="isOpen = true"
        @keydown.escape.window="isOpen = false"
        x-show="isOpen"
        style="display: none;"
        class="fixed inset-0 z-60 w-screen h-screen flex justify-center items-center"
    >
        <!-- overlay -->
        <div 
            class="absolute inset-0 bg-neutral-800/50 backdrop-blur-xs"
            @click="isOpen = false"
            x-show="isOpen"
            x-transition.opacity
        ></div>
        
        <!-- modal -->
        <div 
            class="relative bg-white p-8 rounded-lg shadow-lg border-t-blue-900 border-t-4 w-full max-w-sm mx-4 z-10"
            x-show="isOpen"
            x-transition
        >
            <!-- close button -->
            <button 
                @click="isOpen = false" 
                class="absolute top-3 right-3 text-neutral-400 hover:text-neutral-600 transition-colors focus:outline-none"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h3 class="text-xl font-semibold text-gray-900 text-center mb-8">Login</h3>
            {{-- <p class="mb-6 text-gray-600 text-sm">Please select how you would like to sign in.</p> --}}
            
            <!-- button container -->
            <div class="flex flex-col gap-3 w-full">
                
                <!-- user login -->
                <a href="{{ route('auth.student-login') }}" class="w-full px-4 py-2 bg-blue-900 text-white font-medium rounded-full hover:bg-blue-900/90 focus:outline-none focus:ring focus:ring-blue-900 focus:ring-offset-2 transition-colors text-center">
                    Student Login
                </a>
                
                <!-- staff login -->
                <a href="{{ route('auth.staff-login') }}" class="w-full px-4 py-2 border-2 border-blue-900 text-blue-900 font-medium bg-transparent rounded-full hover:bg-sky-200/35 focus:outline-none focus:ring focus:ring-blue-900 focus:ring-offset-2 transition-colors text-center">
                    Staff Login
                </a>
                
            </div>
        </div>
    </div>
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