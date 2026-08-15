<x-guest.layout class="bg-sky-200 py-10">
    {{-- 
      Add x-cloak style block to prevent Alpine flickering before it loads.
      You can also place this in your main CSS file. 
    --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg" 
         x-data="{ step: 1, totalSteps: 5 }" 
         x-cloak>
        
        <!-- Form Header & Progress Indicator -->
        <div class="mb-8 border-b pb-4y">
            <h1 class="text-3xl font-bold text-gray-800">Enrollment Form</h1>
            
            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">
                    Step <span x-text="step"></span> of <span x-text="totalSteps"></span>
                </span>
                
                <!-- Simple Progress Bar -->
                <div class="w-1/2 bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
                         :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
                </div>
            </div>
        </div>

        <form action="/enroll" method="POST">
            @csrf

            <!-- ========================================== -->
            <!-- STEP 1: ACADEMIC & ONLINE ACCESS           -->
            <!-- ========================================== -->
            <div x-show="step === 1" x-transition.opacity.duration.300ms>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">1. Academic & Online Access</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Example Inputs -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Grade Level Enrolling For</label>
                        <select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Grade</option>
                            <option value="preschool">Preschool</option>
                            <option value="kindergarten">Kindergarten</option>
                            <option value="1">Grade 1</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Do you have access to ONLINE LEARNING?</label>
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="online_access" value="no" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700">No</span>
                            </label><br>
                            <label class="inline-flex items-center">
                                <input type="radio" name="online_access" value="wifi" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700">Yes, Wi-Fi data plan</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 2: PAYMENT SCHEME                     -->
            <!-- ========================================== -->
            <div x-show="step === 2" x-transition.opacity.duration.300ms>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">2. Payment Scheme</h2>
                
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Choose your payment scheme:</label>
                    
                    <div class="p-4 border rounded-md bg-gray-50">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_scheme" value="full" class="text-blue-600">
                            <span class="ml-2 font-medium text-gray-800">Full Payment</span>
                        </label>
                        <p class="ml-6 text-sm text-gray-500 mt-1">Includes Tuition + Misc + Books, less 10% of Tuition Fee.</p>
                    </div>

                    <!-- Add other scheme options here -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 3: STUDENT PERSONAL INFORMATION       -->
            <!-- ========================================== -->
            <div x-show="step === 3" x-transition.opacity.duration.300ms>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">3. Student Personal Information</h2>
                <p class="text-sm text-red-500 mb-4">* Please type names exactly as they appear on the Birth Certificate, in CAPITAL LETTERS.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" class="mt-1 block w-full uppercase rounded-md border-gray-300 shadow-sm" placeholder="DELA CRUZ">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" class="mt-1 block w-full uppercase rounded-md border-gray-300 shadow-sm" placeholder="JUAN">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" name="middle_name" class="mt-1 block w-full uppercase rounded-md border-gray-300 shadow-sm" placeholder="SANTOS">
                    </div>
                    
                    <!-- Continue with other fields (Birthdate, Address, etc.) -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 4: FAMILY BACKGROUND                  -->
            <!-- ========================================== -->
            <div x-show="step === 4" x-transition.opacity.duration.300ms>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">4. Family Background</h2>
                
                <div class="space-y-6">
                    <!-- Father section -->
                    <div class="p-4 border rounded-lg bg-gray-50">
                        <h3 class="font-medium text-gray-900 border-b pb-2 mb-4">Father's Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700">Last Name</label>
                                <input type="text" name="father_last_name" class="mt-1 block w-full uppercase rounded-md border-gray-300 shadow-sm">
                            </div>
                            <!-- Add First Name, Occupation, etc. -->
                        </div>
                    </div>
                    
                    <!-- Repeat for Mother, Guardian, Primary Contact -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- STEP 5: EDUCATIONAL BACKGROUND             -->
            <!-- ========================================== -->
            <div x-show="step === 5" x-transition.opacity.duration.300ms>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">5. Applicant's Educational Background</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last School Attended</label>
                        <input type="text" name="last_school" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <!-- Add School Year, Honors, Average, etc. -->
                </div>
            </div>

            <!-- ========================================== -->
            <!-- NAVIGATION BUTTONS                         -->
            <!-- ========================================== -->
            <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-between">
                
                <!-- Previous Button: Hidden on Step 1 -->
                <div>
                    <button type="button" 
                            x-show="step > 1" 
                            @click="step--" 
                            class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        &larr; Previous
                    </button>
                </div>

                <!-- Next / Submit Buttons -->
                <div>
                    <!-- Next Button: Hidden on Last Step -->
                    <button type="button" 
                            x-show="step < totalSteps" 
                            @click="step++" 
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Next &rarr;
                    </button>

                    <!-- Submit Button: Only Visible on Last Step -->
                    <button type="submit" 
                            x-show="step === totalSteps" 
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Submit Enrollment Form
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-guest.layout>

{{-- <x-guest.layout class="bg-sky-200">
  <h1>Enrollment Form</h1>
  
    1. email

    2. Enrolling for Grade Level (Pre, Kin, etc.)

    3. Learner Reference Number (LRN)
       For Grade 1 to Grade 10 Enrollees, LRN can be found on the pupil's report card (SF9). (For Preschoolers and Kindergarten Enrollees, use the cellphone number of the parent)

    4. Do you have access to ONLINE LEARNING?
      - No
      - Yes, we are subscribed to a wifi data plan (e.g. PLDT, Globe Broadband)
      - Yes, we are subscribed to a mobile data plan (postpaid)
      - Yes, we buy cellular data when we need to connect to the internet (prepaid)

    5. If yes, specify your available gadgets.
      - smartphone
      - laptop
      - pc
      - tablet
      - ipad
      - mac
      - smart tv
      - others

    Payment Scheme
    6. Choose your payment scheme Note: full payment = (Tuition Fee + Miscellaneous + Books) less 10% of Tuition Fee

    Note: Special payment arrangement is subject for approval. Kindly talk to the principal or to the school administrator to request for special payment scheme.

    (Likert [full payment, Option 1, Option 2, Special Payment Scheme])

    Personal Info
    Personal Information of the Student (Magbase sa kung ano ang nakasulat sa Birth Certificate)
    7. Last Name
    *type in CAPITAL LETTERS
    8. First Name
    9. Middle Name
    10. Gender
    11. Birthdate (dd/mm/yyyy)
    12. Place of Birth (Minucipal/City, Province)
    13. Birth Order (e.g. 1st, 2nd, 3rd,..)
    14. Age
    15. Nationality
    16. House No.
    17. Sitio/Subdivision
    18. Barangay (e.g. Kumintang Ibaba)
    19. Province
    20. Zip Code
    21. Region
    22. Landline Number (if any)
    *Please do not provide cellphone number. Input "N/A" if you don't have any.

    Family Background
    CAPITAL
    Father
      23. Desceased (Yes/No)
      24. last Name
      25. first
      26. middle
      26. age
      27. address
      28. contact
      29. occupation
    Mother
      30. maiden last
      31. first
      32. maiden middle
      33. age
      34. address
      35. contact
      36. occupation
    Guardian
      37. Guardian's Name (If any)
      *type in CAPITAL LETTERS with this format NAME MI. SURNAME
      38. relation
      39. address
      40. contact
      41. occupation
      42. Full Name of Person to Communicate with GSCAB regarding student's schooling.
      43. Relationship to the Student
      44. Complete Address

      Applicant's edu background
      44. Last school attended
      45. School Address
      46. Year Attended (School Year)
      47. Honors/Awards and Citations Received
      48. School Type (guest/Private)
      49. General Average
      50. Special Talent/Skills

 
</x-guest.layout> --}}