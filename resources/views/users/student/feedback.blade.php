<x-layouts.app 
    title="Student | Feedback" 
    header="Submit Feedback"
    class="flex flex-col p-4 md:p-6 gap-y-6"
>
    
    <div class="max-w-3xl">
        <p class="text-gray-600 dark:text-neutral-100">
            Share your thoughts and suggestions or report an issue or concern, we appreciate your feedback.
        </p>
    </div>

    {{-- action route('') --}}
    <form action="" method="POST" class="w-full max-w-3xl flex flex-col gap-y-4">
        @csrf
        
        <div class="flex flex-col gap-y-1">
            <label for="message" class="sr-only">Feedback Message</label>
            
        <textarea 
            name="message" 
            id="message"
            rows="6"
            placeholder="Tell us what's on your mind..."
            required
            class="w-full px-3 py-2 rounded-md bg-white border outline-none transition-colors resize-none dark:placeholder-gray-500 dark:text-black
                  @error('message') border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-200 
                  @else border-gray-300 focus:border-blue-900 focus:ring-1 focus:ring-sky-200 @enderror"
        >{{ old('message') }}</textarea>

            {{-- validation error --}}
            @error('message')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="inline-flex items-center justify-center gap-2 bg-blue-900 px-6 py-2 rounded-md self-end text-neutral-50 font-medium hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-900">
            Submit 
            
            {{--
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
            --}}
        </button>
    </form>
</x-layouts.app>