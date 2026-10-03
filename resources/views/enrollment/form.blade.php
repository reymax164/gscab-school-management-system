<x-form.layout class="py-10">

    <style>
      [x-cloak] { display: none !important; }
    </style>

    @php
        // Maps each step to the field names it contains, used to jump to the step with the validation error.
        $stepFields = [
            1 => ['student_status', 'email', 'grade_level', 'lrn', 'online_access', 'gadgets'],
            2 => ['payment_scheme'],
            3 => ['last_name', 'first_name', 'middle_name', 'gender', 'birthdate', 'age', 'birthplace', 'birth_order', 'nationality', 'house_no', 'sitio_subdivision', 'barangay', 'zip', 'religion', 'landline'],
            4 => ['father_deceased', 'father_last_name', 'father_first_name', 'father_middle_name', 'father_age', 'father_address', 'father_number', 'father_occupation', 'mother_deceased', 'mother_maiden_last', 'mother_first_name', 'mother_maiden_middle', 'mother_age', 'mother_address', 'mother_number', 'mother_occupation', 'guardian_name', 'guardian_relation', 'guardian_address', 'guardian_number', 'guardian_occupation', 'con_person_name', 'con_person_relation', 'con_person_number', 'con_person_address'],
            5 => ['last_school', 'last_school_address', 'last_school_year', 'last_school_type', 'gen_ave', 'talent_skills'],
        ];

        $initialStep = 1;
        $errorField = null;

        if ($errors->any()) {
            $firstErrorKey = explode('.', collect($errors->keys())->first())[0];

            foreach ($stepFields as $stepNumber => $fields) {
                if (in_array($firstErrorKey, $fields, true)) {
                    $initialStep = $stepNumber;
                    $errorField = $firstErrorKey;
                    break;
                }
            }
        }
    @endphp

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg" 
      x-data="{ 
          step: {{ $initialStep }}, 
          totalSteps: 6,
          reviewData: [],
          scrollToTop() {
              const formElement = document.getElementById('enrollment-form');
              const offsetPosition = formElement.getBoundingClientRect().top + window.scrollY - 50;
              window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
          },
          validateAndNext() {
              let currentStepDiv = document.getElementById('step-' + this.step);
              let inputs = Array.from(currentStepDiv.querySelectorAll('input[required], select[required]'));
              let isValid = true;
              
              for (let input of inputs) {
                  if (!input.checkValidity()) {
                      input.reportValidity();
                      isValid = false;
                      break; 
                  }
              }
              
              if (isValid) {
                  this.step++;
                  if (this.step === this.totalSteps) {
                      this.updateReviewDetails();
                  }
                  this.scrollToTop();
              }
          },
          updateReviewDetails() {
              const form = document.getElementById('enrollment-form');
              const formData = new FormData(form);
              this.reviewData = [];
              for (let [key, value] of formData.entries()) {
                  if(key !== '_token' && value) {
                      // Format the key to be more readable (e.g., 'first_name' -> 'First Name')
                      let formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                      // Change checkbox 'on' values to 'Yes'
                      let displayValue = value === 'on' ? 'Yes' : value;
                      this.reviewData.push({ key: formattedKey, value: displayValue });
                  }
              }
          }
      }" 
      x-cloak
      @if($errorField)
      x-init="$nextTick(() => {
          const errorBlock = document.getElementById('error-block');
          if (errorBlock) { errorBlock.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
          const el = document.querySelector('[name=&quot;{{ $errorField }}&quot;], [name=&quot;{{ $errorField }}[]&quot;]');
          if (el) { el.focus({ preventScroll: true }); }
      })"
      @endif>
        
      <!-- form header & progress indicator -->
      <div class="mb-8 border-b pb-4 flex flex-col md:flex-row">
        <img 
          src="{{ asset('images/gscab-logo.webp') }}" 
          alt="GSCAB logo" 
          class="h-32 md:h-24 w-auto object-contain md:mr-4 mb-6 md:mb-0"
          fetchpriority="high"
          loading="eager"
          height=64
          width=64
        />

        <div>
          <h1 class="text-3xl font-bold text-gray-800">Enrollment Form</h1>
        
          <div class="mt-4 mb-1 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">
              Step <span x-text="step"></span> of <span x-text="totalSteps"></span>
            </span>
              
            <!--  progress bar -->
            <div class="w-1/2 bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
                  :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
            </div>
        </div>
        
        </div>
      </div>

        <form id="enrollment-form" action="/enroll" method="POST">
            @csrf

            {{-- error block --}}
            @if ($errors->any())
                <div id="error-block" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                    <h3 class="text-sm font-medium text-red-800">Oops! We found some errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

          {{-- Section 1: ACADEMIC AND ONLINE ACCESS --}}
          <div id="step-1" x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
              <h2 class="text-xl font-bold text-gray-800 mb-6">Academic & Online Access</h2>
                
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">

                  {{-- Student Status --}}
                  <div class="md:col-span-2 border-b border-gray-200 pb-5">
                      <label class="block text-sm font-semibold text-gray-800 mb-3">Student Type</label>
                      <div class="flex items-center space-x-6">
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="student_status" value="new" required @checked(old('student_status') === 'new') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">New</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="student_status" value="existing" required @checked(old('student_status') === 'existing') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Existing</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="student_status" value="transferee" required @checked(old('student_status') === 'transferee') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Transferee</span>
                          </label>
                      </div>
                  </div>

                  {{-- Email --}}
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Email Address</label>
                      <input type="email" name="email" required value="{{ old('email') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>

                  {{-- Grade Level --}}
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Grade Level Enrolling For</label>
                      <select name="grade_level" required class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          <option value="" disabled @selected(! old('grade_level'))>Select Grade</option>
                          <option value="preschool" @selected(old('grade_level') == 'preschool')>Preschool</option>
                          <option value="kindergarten" @selected(old('grade_level') == 'kindergarten')>Kindergarten</option>
                          <option value="1" @selected(old('grade_level') == '1')>Grade 1</option>
                          <option value="2" @selected(old('grade_level') == '2')>Grade 2</option>
                          <option value="3" @selected(old('grade_level') == '3')>Grade 3</option>
                          <option value="4" @selected(old('grade_level') == '4')>Grade 4</option>
                          <option value="5" @selected(old('grade_level') == '5')>Grade 5</option>
                          <option value="6" @selected(old('grade_level') == '6')>Grade 6</option>
                          <option value="7" @selected(old('grade_level') == '7')>Grade 7</option>
                          <option value="8" @selected(old('grade_level') == '8')>Grade 8</option>
                          <option value="9" @selected(old('grade_level') == '9')>Grade 9</option>
                          <option value="10" @selected(old('grade_level') == '10')>Grade 10</option>
                      </select>
                  </div>
                  
                  {{-- LRN --}}
                  <div>
                      <label for="lrn" class="block text-sm font-semibold text-gray-800 mb-2">Learner Reference Number (LRN)</label>
                      <input type="number" name="lrn" id="lrn" 
                          min="0" required
                          value="{{ old('lrn') }}"
                          onwheel="this.blur()" 
                          onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();"
                          class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  </div>

                  <p class="text-xs self-end text-gray-600">
                    For Grade 1 to Grade 10 Enrollees, LRN can be found on the pupil's report card (SF9). (For Preschoolers and Kindergarten Enrollees, use the cellphone number of the parent)
                  </p>

                  {{-- Access to Online Learning --}}
                  <div class="md:col-span-2 mt-2">
                      <label class="block text-sm font-semibold text-gray-800 mb-3">Do you have access to ONLINE LEARNING?</label>
                      <div class="space-y-3">
                          {{-- No --}}
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="online_access" value="no" required @checked(old('online_access') === 'no') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">No</span>
                          </label><br>

                          {{-- Yes, (wifi data plan) --}}
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="online_access" value="wifi" required @checked(old('online_access') === 'wifi') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Yes, we are subscribed to a wifi data plan (e.g. PLDT, Globe Broadband)</span>
                          </label><br>

                          {{-- Yes, (mobile data plan) --}}
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="online_access" value="postpaid" required @checked(old('online_access') === 'postpaid') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Yes, we are subscribed to a mobile data plan (postpaid)</span>
                          </label><br>

                          {{-- Yes, (cellular data) --}}
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="radio" name="online_access" value="prepaid" required @checked(old('online_access') === 'prepaid') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Yes, we buy cellular data when we need to connect to the internet (prepaid)</span>
                          </label>
                      </div>
                  </div>

                  {{-- Available Gadgets --}}
                  <div class="md:col-span-2 mt-2">
                      <label class="block text-sm font-semibold text-gray-800 mb-4">If yes, specify your available gadgets.</label>

                      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="Smartphone" @checked(in_array('Smartphone', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Smartphone</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="Laptop" @checked(in_array('Laptop', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Laptop</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="PC" @checked(in_array('PC', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">PC</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="Tablet" @checked(in_array('Tablet', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Tablet</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="iPad" @checked(in_array('iPad', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">iPad</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="Mac" @checked(in_array('Mac', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Mac</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="Smart TV" @checked(in_array('Smart TV', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Smart TV</span>
                          </label>
                          <label class="inline-flex items-center cursor-pointer">
                              <input type="checkbox" name="gadgets[]" value="Others" @checked(in_array('Others', old('gadgets', []))) class="rounded border border-gray-400 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-4 w-4">
                              <span class="ml-2 text-sm text-gray-700">Others</span>
                          </label>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Section 2: PAYMENT SCHEME --}}
          <div id="step-2" x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
              <h2 class="text-xl font-bold text-gray-800 mb-6">Payment Scheme</h2>
              
              <div class="space-y-4">
                  <p class="block text-sm font-semibold text-gray-800 mb-3">Choose your payment scheme:</p>
                  
                  {{-- full payment --}}
                  <label class="block p-4 border border-gray-300 rounded-md bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-gray-400 transition-colors">
                      <div class="flex items-start">
                          <div class="flex items-center h-5">
                              <input type="radio" name="payment_scheme" value="full" required @checked(old('payment_scheme') === 'full') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4 cursor-pointer">
                          </div>
                          <div class="ml-3">
                              <span class="block text-sm font-semibold text-gray-800">Full Payment</span>
                              <p class="text-sm text-gray-600 mt-1">Includes Tuition + Misc + Books, less 10% of Tuition Fee.</p>
                          </div>
                      </div>
                  </label>
                  
                  {{-- option 1 --}}
                  <label class="block p-4 border border-gray-300 rounded-md bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-gray-400 transition-colors">
                      <div class="flex items-start">
                          <div class="flex items-center h-5">
                              <input type="radio" name="payment_scheme" value="option1" required @checked(old('payment_scheme') === 'option1') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4 cursor-pointer">
                          </div>
                          <div class="ml-3">
                              <span class="block text-sm font-semibold text-gray-800">Option 1</span>
                              <p class="text-sm text-gray-600 mt-1">Includes Books + Misc.</p>
                          </div>
                      </div>
                  </label>
                  
                  {{-- option 2 --}}
                  <label class="block p-4 border border-gray-300 rounded-md bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-gray-400 transition-colors">
                      <div class="flex items-start">
                          <div class="flex items-center h-5">
                              <input type="radio" name="payment_scheme" value="option2" required @checked(old('payment_scheme') === 'option2') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4 cursor-pointer">
                          </div>
                          <div class="ml-3">
                              <span class="block text-sm font-semibold text-gray-800">Option 2</span>
                              <p class="text-sm text-gray-600 mt-1">Includes Books + 1/4 Misc.</p>
                          </div>
                      </div>
                  </label>
                              
                  {{-- special --}}
                  <label class="block p-4 border border-gray-300 rounded-md bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-gray-400 transition-colors">
                      <div class="flex items-start">
                          <div class="flex items-center h-5">
                              <input type="radio" name="payment_scheme" value="special" required @checked(old('payment_scheme') === 'special') class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4 cursor-pointer">
                          </div>
                          <div class="ml-3">
                              <span class="block text-sm font-semibold text-gray-800">Special Payment Scheme</span>
                              <p class="text-sm text-gray-600 mt-1">Special payment arrangement is subject for approval. Kindly talk to the principal or to the school administrator to request for a special payment scheme.</p>
                          </div>
                      </div>
                  </label>
              </div>
          </div>

          {{-- Section 3: PERSONAL INFORMATION --}}
          <div id="step-3" x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
              <h2 class="text-xl font-bold text-gray-800 mb-2">Student Personal Information</h2>
              <p class="text-sm font-semibold text-red-600 mb-6">* Please type names exactly as they appear on the Birth Certificate, in CAPITAL LETTERS.<br>(Magbase sa kung ano ang nakasulat sa Birth Certificate)</p>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6">
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Last Name</label>
                      <input type="text" name="last_name" required value="{{ old('last_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="DELA CRUZ">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">First Name</label>
                      <input type="text" name="first_name" required value="{{ old('first_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="JUAN">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Middle Name</label>
                      <input type="text" name="middle_name" required value="{{ old('middle_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="SANTOS">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Gender</label>
                      <select name="gender" required class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          <option value="" disabled @selected(! old('gender'))>Choose Gender</option>
                          <option value="male" @selected(old('gender') == 'male')>Male</option>
                          <option value="female" @selected(old('gender') == 'female')>Female</option>
                      </select>
                  </div>
                  <div x-data="{ 
                      birthdate: '{{ old('birthdate') }}', 
                      calculateAge() { 
                          if (!this.birthdate) return '';
                          let b = new Date(this.birthdate);
                          let age = new Date().getFullYear() - b.getFullYear();
                          let m = new Date().getMonth() - b.getMonth();
                          if (m < 0 || (m === 0 && new Date().getDate() < b.getDate())) age--;
                          $refs.ageInput.value = age;
                      } 
                  }" x-init="calculateAge()">
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Birthdate</label>
                      <input type="date" name="birthdate" required x-model="birthdate" @change="calculateAge()" value="{{ old('birthdate') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Age</label>
                      <input type="number" name="age" required x-ref="ageInput" readonly value="{{ old('age') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm bg-gray-100 text-gray-600 focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="Auto-calculated">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Place of Birth</label>
                      <input type="text" name="birthplace" required value="{{ old('birthplace') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Municipality/City, Province">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Birth Order</label>
                      <input type="text" name="birth_order" required value="{{ old('birth_order') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. 1st, 2nd, 3rd">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Nationality</label>
                      <input type="text" name="nationality" required value="{{ old('nationality', 'Filipino') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">House No.</label>
                      <input type="text" name="house_no" required value="{{ old('house_no') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Sitio/Subdivision</label>
                      <input type="text" name="sitio_subdivision" required value="{{ old('sitio_subdivision') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-2">Barangay</label>
                      <input type="text" name="barangay" required value="{{ old('barangay') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Kumintang Ibaba">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-1">Zip Code</label>
                      <span class="text-xs text-gray-500 mb-2 block">*Input 4200 if Batangas City</span>
                      <input type="text" name="zip" required value="{{ old('zip') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-1">Religion</label>
                      <span class="text-xs text-transparent mb-2 block pointer-events-none">&nbsp;</span>
                      <input type="text" name="religion" required value="{{ old('religion') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  <div>
                      <label class="block text-sm font-semibold text-gray-800 mb-1">Landline Number (if any)</label>
                      <span class="text-xs text-gray-500 mb-2 block">*No cellphones. Input "N/A" if none.</span>
                      <input type="text" name="landline" required value="{{ old('landline') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="N/A">
                  </div>
              </div>
          </div>

          {{-- Section 4: FAMILY BACKGROUND --}}
          <div id="step-4" x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
              <h2 class="text-xl font-bold text-gray-800 mb-6">Family Background</h2>
              
              <div class="space-y-8">
                  {{-- father section --}}
                  <div class="p-5 border border-gray-300 rounded-lg bg-gray-50 shadow-sm" x-data="{ deceased: '{{ old('father_deceased', 'no') }}' }">
                      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-300 pb-3 mb-5 gap-3">
                          <h3 class="text-lg font-bold text-gray-800">Father's Information</h3>
                          
                          <div class="flex items-center gap-4 bg-white px-3 py-1.5 rounded-md border border-gray-200">
                              <span class="text-sm font-semibold text-gray-800">Deceased?</span>
                              <label class="inline-flex items-center cursor-pointer">
                                  <input type="radio" name="father_deceased" value="yes" required x-model="deceased" class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                                  <span class="ml-1.5 text-sm text-gray-700">Yes</span>
                              </label>
                              <label class="inline-flex items-center cursor-pointer">
                                  <input type="radio" name="father_deceased" value="no" required x-model="deceased" class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                                  <span class="ml-1.5 text-sm text-gray-700">No</span>
                              </label>
                          </div>
                      </div>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Last Name</label>
                              <input type="text" name="father_last_name" required value="{{ old('father_last_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">First Name</label>
                              <input type="text" name="father_first_name" required value="{{ old('father_first_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Middle Name</label>
                              <input type="text" name="father_middle_name" required value="{{ old('father_middle_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Age</label>
                              <input type="number" name="father_age" required min="0" value="{{ old('father_age') }}" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Address</label>
                              <input type="text" name="father_address" required value="{{ old('father_address') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Contact Number</label>
                              <input type="number" name="father_number" required :disabled="deceased === 'yes'" :class="deceased === 'yes' ? 'bg-gray-100 cursor-not-allowed' : ''" min="0" value="{{ old('father_number') }}" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Occupation</label>
                              <input type="text" name="father_occupation" required :disabled="deceased === 'yes'" :class="deceased === 'yes' ? 'bg-gray-100 cursor-not-allowed' : ''" value="{{ old('father_occupation') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                      </div>
                  </div>

                  {{-- mother section --}}
                  <div class="p-5 border border-gray-300 rounded-lg bg-gray-50 shadow-sm" x-data="{ deceased: '{{ old('mother_deceased', 'no') }}' }">
                      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-300 pb-3 mb-5 gap-3">
                          <h3 class="text-lg font-bold text-gray-800">Mother's Information</h3>
                          
                          <div class="flex items-center gap-4 bg-white px-3 py-1.5 rounded-md border border-gray-200">
                              <span class="text-sm font-semibold text-gray-800">Deceased?</span>
                              <label class="inline-flex items-center cursor-pointer">
                                  <input type="radio" name="mother_deceased" value="yes" required x-model="deceased" class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                                  <span class="ml-1.5 text-sm text-gray-700">Yes</span>
                              </label>
                              <label class="inline-flex items-center cursor-pointer">
                                  <input type="radio" name="mother_deceased" value="no" required x-model="deceased" class="text-blue-600 border border-gray-400 focus:ring-blue-500 h-4 w-4">
                                  <span class="ml-1.5 text-sm text-gray-700">No</span>
                              </label>
                          </div>
                      </div>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Maiden Last Name</label>
                              <input type="text" name="mother_maiden_last" required value="{{ old('mother_maiden_last') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">First Name</label>
                              <input type="text" name="mother_first_name" required value="{{ old('mother_first_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Maiden Middle Name</label>
                              <input type="text" name="mother_maiden_middle" required value="{{ old('mother_maiden_middle') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Age</label>
                              <input type="number" name="mother_age" required min="0" value="{{ old('mother_age') }}" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Address</label>
                              <input type="text" name="mother_address" required value="{{ old('mother_address') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Contact Number</label>
                              <input type="number" name="mother_number" required :disabled="deceased === 'yes'" :class="deceased === 'yes' ? 'bg-gray-100 cursor-not-allowed' : ''" min="0" value="{{ old('mother_number') }}" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Occupation</label>
                              <input type="text" name="mother_occupation" required :disabled="deceased === 'yes'" :class="deceased === 'yes' ? 'bg-gray-100 cursor-not-allowed' : ''" value="{{ old('mother_occupation') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                      </div>
                  </div>

                  {{-- guardian section --}}
                  <div class="p-5 border border-gray-300 rounded-lg bg-gray-50 shadow-sm">
                      <h3 class="text-lg font-bold text-gray-800 border-b border-gray-300 pb-3 mb-5">Guardian's Information</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Guardian's Name (If any)</label>
                              <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Relation to Student</label>
                              <input type="text" name="guardian_relation" value="{{ old('guardian_relation') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Address</label>
                              <input type="text" name="guardian_address" value="{{ old('guardian_address') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Contact Number</label>
                              <input type="number" name="guardian_number" value="{{ old('guardian_number') }}" min="0" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Occupation</label>
                              <input type="text" name="guardian_occupation" value="{{ old('guardian_occupation') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                      </div>
                  </div>

                  {{-- contact person section --}}
                  <div class="p-5 border border-gray-300 rounded-lg bg-gray-50 shadow-sm">
                      <h3 class="text-lg font-bold text-gray-800 border-b border-gray-300 pb-3 mb-5">Contact Person</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                          <div class="md:col-span-2">
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Full Name of Person to Communicate with GSCAB regarding student's schooling</label>
                              <input type="text" name="con_person_name" required value="{{ old('con_person_name') }}" class="text-sm px-3 py-2 block w-full uppercase rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Relationship to the Student</label>
                              <input type="text" name="con_person_relation" required value="{{ old('con_person_relation') }}" class="text-sm px-3 py-2 block w-full  rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                          <div>
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Contact Number</label>
                              <input type="number" name="con_person_number" required min="0" value="{{ old('con_person_number') }}" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                          </div>
                          <div class="md:col-span-2">
                              <label class="block text-sm font-semibold text-gray-800 mb-2">Complete Address</label>
                              <input type="text" name="con_person_address" required value="{{ old('con_person_address') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                          </div>
                      </div>
                  </div>

              </div>
          </div>

          {{-- Section 5: EDUCATIONAL BACKGROUND --}}
          <div id="step-5" x-show="step === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
              <h2 class="text-xl font-bold text-gray-800 mb-6">Applicant's Educational Background</h2>
              
              <div class="p-5 border border-gray-300 rounded-lg bg-gray-50 shadow-sm">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                      
                      <div class="md:col-span-2">
                          <label class="block text-sm font-semibold text-gray-800 mb-2">Last School Attended</label>
                          <input type="text" name="last_school" required value="{{ old('last_school') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                      </div>
                      
                      <div class="md:col-span-2">
                          <label class="block text-sm font-semibold text-gray-800 mb-2">School Address</label>
                          <input type="text" name="last_school_address" required value="{{ old('last_school_address') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                      </div>
                      
                      <div>
                          <label class="block text-sm font-semibold text-gray-800 mb-2">Year Attended (School Year)</label>
                          <input type="text" name="last_school_year" required value="{{ old('last_school_year') }}" placeholder="e.g., 2022-2023" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                      </div>

                      <div>
                          <label class="block text-sm font-semibold text-gray-800 mb-2">School Type</label>
                          <select name="last_school_type" required class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
                              <option value="" @selected(! old('last_school_type')) disabled>Choose</option>
                              <option value="public" @selected(old('last_school_type') == 'public')>Public</option>
                              <option value="private" @selected(old('last_school_type') == 'private')>Private</option>
                          </select>
                      </div> 
                      
                      <div>
                          <label class="block text-sm font-semibold text-gray-800 mb-2">General Average</label>
                          <input type="number" name="gen_ave" required min="0" max="100" step="0.01" value="{{ old('gen_ave') }}" onwheel="this.blur()" onkeydown="if(event.key==='-' || event.key==='+') event.preventDefault();" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                      </div>
                      
                      <div>
                          <label class="block text-sm font-semibold text-gray-800 mb-2">Special Talent/Skills</label>
                          <input type="text" name="talent_skills" required value="{{ old('talent_skills') }}" class="text-sm px-3 py-2 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                      </div>

                  </div>
              </div>
          </div>

          {{-- Section 6: REVIEW INFORMATION --}}
          <div id="step-6" x-show="step === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
              <h2 class="text-xl font-bold text-gray-800 mb-2">Review Your Information</h2>
              <p class="text-sm font-semibold text-gray-600 mb-6">Please check if all the details below are correct before submitting the form.</p>
              
              <div class="p-6 border border-gray-300 rounded-lg bg-gray-50 shadow-sm max-h-96 overflow-y-auto">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                      <template x-for="(item, index) in reviewData" :key="index">
                          <div class="border-b border-gray-200 pb-2">
                              <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider" x-text="item.key"></span>
                              <span class="block text-sm font-medium text-gray-900 mt-1" x-text="item.value"></span>
                          </div>
                      </template>
                  </div>
              </div>
          </div>

            {{-- buttons --}}
            <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-between">
                
                <!-- previous button-->
                <div>
                    <button type="button" 
                            x-show="step > 1" 
                            @click="step--; scrollToTop()" 
                            class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                         Previous
                    </button>
                </div>

                <!-- next / submit button -->
                <div>
                    <!-- next button -->
                    <button type="button" 
                            x-show="step < totalSteps" 
                            @click="validateAndNext()" 
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Next
                    </button>

                    <!-- submit button -->
                    <button type="submit" 
                            x-show="step === totalSteps" 
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Submit
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-form.layout>