<x-layouts.auth title="Staff Login">

  <div class="relative w-full max-w-md mx-auto bg-white p-8 rounded-xl shadow-lg border-t-blue-900 border-t-6">
    <h1 class="text-2xl text-gray-800 font-semibold text-center mb-6">Staff Portal</h1>
    
    <img src="{{ asset('images/gscab-logo.webp') }}" alt="GSCAB Logo" class="mx-auto block h-24 w-auto mb-6 object-contain">

      <form action="{{ route('auth.staff.authenticate') }}" method="POST" class="space-y-4">
      @csrf 

      @if(session('require_2fa'))
        
        <div class="text-center mb-4">
            <p class="text-sm text-gray-600">
                Please open your authenticator app and enter the 6-digit code to verify your identity.
            </p>
        </div>

        <div>
            <label for="totp_code" class="block text-sm font-medium text-gray-700 mb-1">
                Authentication Code
            </label>
            <input type="text" 
                   name="totp_code" 
                   id="totp_code" 
                   required 
                   autofocus
                   maxlength="6"
                   placeholder="123456"
                   class="w-full border-gray-300 rounded-md shadow-sm px-3 py-2 border tracking-widest text-center text-lg focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900">
            
            @error('totp_code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- hidden field to retain the email during the 2FA submission --}}
        <input type="hidden" name="email" value="{{ session('email') }}">

      @else

        {{-- email field --}}
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email Address
          </label>
          <input type="email" 
                 name="email" 
                 id="email" 
                 value="{{ old('email') }}" 
                 required 
                 autofocus
                 class="lowercase w-full border-gray-300 rounded-md shadow-sm px-3 py-2 border focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900">
          
          {{-- error --}}
          @error('email')
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
      @endif

      {{-- Action Button --}}
      <div class="pt-4">
        <button type="submit" 
                class="w-full inline-flex items-center justify-center px-8 py-2 bg-blue-900 text-white font-bold rounded-full hover:bg-blue-900 transition-colors duration-200">
          {{ session('require_2fa') ? 'Verify & Log In' : 'Log In' }}
        </button>
      </div>

    </form>
  </div>

</x-layouts.auth>