<x-guest.layout>
  {{-- header --}}
  <div class="w-full flex justify-center items-end py-4 px-4">
    <h1 class="text-center text-xl md:text-2xl font-serif text-primary dark:text-sky-200">
      Good Shepherd Christian Academy of Batangas
    </h1>
  </div>

  {{-- main banner --}}
  <div class="w-full">
    <img src="" alt="main banner" class="h-60 md:h-80 w-full object-cover bg-gray-200">
  </div>

  <div class="w-full max-w-7xl mx-auto p-4 md:p-6 grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
    
    {{-- left section --}}
    <div class="col-span-1 md:col-span-3 flex flex-col sm:flex-row gap-4 items-center sm:items-start">
      
      {{-- left --}}
      <div class="w-full sm:w-48 shrink-0 flex flex-col items-center space-y-4">
        <img src="" alt="logo" class="w-full h-32 object-contain bg-gray-200 rounded-md">
        <a href="{{ route('auth.enroll') }}"
           class="w-full sm:w-fit inline-flex items-center justify-center px-8 py-2 text-blue-900 dark:text-sky-300 border border-blue-900 dark:border-sky-300 font-bold rounded-full hover:bg-blue-900 hover:text-white transition-colors duration-200 text-sm cursor-pointer">
          Enroll
        </a>
      </div>

      {{-- right --}}
      <div class="w-full grow h-40 sm:h-auto min-h-40">
        <img src="" alt="graphic" class="w-full h-full object-cover bg-gray-200 rounded-md">
      </div>

    </div>

    {{-- contact --}}
    <div class="col-span-1 space-y-4 text-sm pt-4 md:pt-0">
      
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

      {{-- <div class="flex items-center gap-x-3">
        <x-icons.messenger class="w-6 h-6 text-blue-900 dark:text-sky-400 shrink-0" />
        <a class="dark:text-slate-200 hover:text-blue-900 hover:underline" href="" target="_blank">GSCAB Messenger</a>
      </div> --}}
    </div>

  </div>

</x-guest.layout>