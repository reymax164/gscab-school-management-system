<x-guest.layout header="Good Shepherd Christian Academy of Batangas">
  <div class="w-full flex justify-center items-end py-4 px-4">
    <h1 class="text-center text-xl md:text-2xl font-serif text-primary dark:text-sky-200">
      Good Shepherd Christian Academy of Batangas
    </h1>
  </div>

  {{-- main banner --}}
  <div class="w-full">
    <img src="" alt="" class="h-60 md:h-80 w-full object-cover bg-gray-200">
  </div>

  <div class="w-full max-w-7xl mx-auto p-4 md:p-6 grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
    
    {{-- left section --}}
    <div class="col-span-1 md:col-span-3 flex flex-col sm:flex-row gap-4 items-center sm:items-start">
      
      {{-- left --}}
      <div class="w-full sm:w-48 shrink-0 flex flex-col items-center space-y-4">
        <img src="images/" alt="" class="w-full h-32  bg-gray-200 object-contain rounded-md">
        <a href="{{ route('auth.enroll') }}"
           class="w-full sm:w-fit inline-flex items-center justify-center px-8 py-2 text-blue-900 dark:text-sky-300 border border-blue-900 dark:border-sky-300 font-bold rounded-full hover:bg-blue-900 hover:text-white transition-colors duration-200 text-sm cursor-pointer">
          Enroll
        </a>
      </div>

      {{-- right --}}
      <div class="w-full grow h-40 sm:h-auto min-h-40">
        <img src="" alt="" class="w-full h-48 object-cover bg-gray-200 rounded-md">
      </div>

    </div>

    {{-- contact --}}
    <div class="col-span-1 space-y-4 text-sm pt-4 md:pt-0">
      <p class="font-serif text-base">Contact Us:</p>
      <div class="flex items-center gap-x-3">
        <x-heroicon-s-map-pin class="w-6 h-6 text-blue-900 dark:text-sky-400 shrink-0" />
        <a class="dark:text-slate-200 hover:text-blue-900 hover:underline" target="_blank"
        href="https://www.google.com/maps/place/Good+Shepherd+Baptist+Church/@13.7671634,121.0581674,17.21z/data=!4m15!1m8!3m7!1s0x33bd055c23d82a93:0xc4284d933193f49!2sArce+Subdivision,+Batangas+City,+Batangas!3b1!8m2!3d13.76878!4d121.0593677!16s%2Fg%2F1vjdmltg!3m5!1s0x33bd055cfb81e419:0x4e0970d0ffb3cd69!8m2!3d13.7663965!4d121.0596092!16s%2Fg%2F1td8wnb_?entry=ttu&g_ep=EgoyMDI2MDgxMi4wIKXMDSoASAFQAw%3D%3D">
        Arce Subdivision, Kumintang Ibaba, Batangas City, Philippines, 4200</a>
      </div>

      <div class="flex items-center gap-x-3">
        <x-heroicon-s-phone class="w-6 h-6 text-blue-900 dark:text-sky-400 shrink-0" />
        <a class="dark:text-slate-200 hover:text-blue-900 hover:underline" href="tel:+639464415058" target="_blank">+63 946 441 5058</a>
      </div>

      <div class="flex items-center gap-x-3">
        <x-heroicon-s-envelope class="w-6 h-6 text-blue-900 dark:text-sky-400 shrink-0" />
        <a class="dark:text-slate-200 hover:text-blue-900 hover:underline" href="mailto:gscab1999@gmail.com">gscab1999@gmail.com</a>
      </div>

      <div class="flex items-center gap-x-3">
        <x-icons.facebook class="w-6 h-6 text-blue-900 dark:text-sky-400 shrink-0" />
        <a class="dark:text-slate-200 hover:text-blue-900 hover:underline" href="https://www.facebook.com/GSCABchristianacademyOFFICIALPAGE" target="_blank">GSCAB Facebook Page</a>
      </div>

    </div>

  </div>

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
              <a href="{{ route('auth.student-login') }}" class="w-full px-4 py-2 bg-blue-900 text-white font-medium rounded-full hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition-colors text-center">
                  Login
              </a>
              
              <!-- staff login -->
              <a href="{{ route('auth.staff-login') }}" class="w-full px-4 py-2 border-2 border-blue-900 text-blue-900 font-medium bg-transparent rounded-full hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition-colors text-center">
                  Staff Login
              </a>
              
          </div>
      </div>
  </div>

</x-guest.layout>