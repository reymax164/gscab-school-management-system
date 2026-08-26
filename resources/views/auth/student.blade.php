<x-layouts.auth title="Login">

  <div class="relative w-full max-w-md bg-white p-8 rounded-md shadow-lg border-t-blue-800 border-t-4">
    
    <h1 class="text-2xl text-gray-800 font-semibold text-center mb-6">Student Portal</h1>
    <img src="{{ asset('images/gscab-logo.webp') }}" alt="GSCAB Logo" class="mx-auto block h-24 w-auto mb-6 object-contain">

      <form action="{{ route('auth.student.authenticate') }}" method="POST" class="space-y-4">
      @csrf 

    {{-- LRN field --}}
      <div>
        <label for="LRN" class="block text-sm font-medium text-gray-700 mb-1">
            Learner Reference Number (LRN)
        </label>
        <input type="text" 
               name="lrn"
               id="LRN" 
               value="{{ old('lrn') }}" 
               required 
               autofocus
               class="w-full border-gray-300 rounded-md shadow-sm px-3 py-2 border focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900">
        
        {{-- error --}}
        @error('lrn')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- password field --}}
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password
        </label>
        <input type="password" 
               name="password" 
               id="password" 
               required 
               class="w-full border-gray-300 rounded-md shadow-sm px-3 py-2 border focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900">
        
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- login button --}}
      <div class="pt-4">
        <button type="submit" 
                class="w-full inline-flex items-center justify-center px-8 py-2 bg-blue-900 text-white font-bold rounded-full hover:bg-blue-800 transition-colors duration-200">
          Log In
        </button>
      </div>

    </form>
  </div>

</x-layouts.auth>