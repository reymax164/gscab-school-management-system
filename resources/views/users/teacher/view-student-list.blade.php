<x-layouts.app title="Teacher | Student List"
               header="Student List"
               class="flex flex-col gap-6 p-4 md:p-6">

    @if(!$class)
      <div class="rounded-md border border-amber-200 bg-amber-50 p-4 text-amber-800">
        Class record not found. Please return to the class list and try again.
      </div>
    @else

      {{-- chosen class header --}}
      <div>
        <h1 class="text-2xl font-semibold text-blue-900">
          {{ $class->subject->name ?? 'Subject TBA' }}
        </h1>
        <p class="text-sm font-medium text-neutral-600">
          Grade {{ $class->grade_level ?? 'N/A' }} 
          @if(!empty($class->section_name))
              - {{ $class->section_name }} 
          @endif
          (S.Y. {{ $class->school_year ?? 'N/A' }})
        </p>
      </div>

      {{-- Student table --}}
      <div class="w-full rounded-lg border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full whitespace-nowrap text-left text-sm">
            <thead class="bg-blue-900 text-white">
              <tr>
                <th scope="col" class="w-16 px-4 py-3 text-center"></th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">Student No. (LRN)</th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">Last Name</th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">First Name</th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">Middle Name</th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">Gender</th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">Guardian</th>
                <th scope="col" class="px-4 py-3 font-semibold tracking-wide">Mobile No.</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-neutral-200 bg-white">
              {{-- loop through students safely --}}
              @forelse ($class->students ?? [] as $student)
                <tr class="transition-colors hover:bg-blue-50/50 even:bg-neutral-100">
                  
                  {{-- Dynamic Avatar based on Gender --}}
                  <td class="px-4 py-3 text-center">
                    @if(strtolower($student->gender ?? '') === 'female')
                        {{-- female avatar --}}
                    @else
                        {{-- male avatar --}}
                    @endif
                  </td>

                  {{-- Student Data --}}
                  <td class="px-4 py-3 font-medium text-neutral-900">{{ $student->lrn ?? 'N/A' }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $student->last_name ?? '-' }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $student->first_name ?? '-' }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $student->middle_name ?? '-' }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ ucfirst($student->gender ?? 'Unknown') }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $student->guardian ?? 'N/A' }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $student->mobile_no ?? 'N/A' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="px-4 py-8 text-center text-neutral-500">
                      No students currently enrolled in this class.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    @endif

</x-layouts.app>