<x-layouts.app title="My Profile" header="My Profile" class="p-4 md:p-6">
    @php $user = $user ?? auth()->user(); @endphp

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- status message --}}
        @if (session('status') === 'profile-photo-updated')
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm rounded-md px-4 py-3">
                Profile photo updated successfully.
            </div>
        @endif

        {{-- profile photo --}}
        <div class="bg-white dark:bg-neutral-800 border border-neutral-400 dark:border-neutral-700 rounded-md p-4 md:p-6 shadow-sm">
            <h2 class="font-semibold text-base md:text-lg text-blue-900 dark:text-blue-400 mb-4">Profile Photo</h2>

            <div class="flex flex-col sm:flex-row items-center gap-6">
                <img
                    id="photoPreview"
                    src="{{ $user->profile_photo_url ?? '' }}"
                    alt="{{ $user->first_name }}'s Profile Photo"
                    class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-full bg-neutral-200 shrink-0 {{ $user->profile_photo_url ? '' : 'hidden' }}"
                />
                <x-heroicon-s-user-circle
                    id="photoPlaceholder"
                    class="w-24 h-24 md:w-32 md:h-32 text-gray-400 dark:text-neutral-500 shrink-0 {{ $user->profile_photo_url ? 'hidden' : '' }}"
                />

                <form id="photoForm" action="{{ route('auth.profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="flex-1 w-full space-y-2">
                    @csrf

                    {{-- holds the cropped square image produced by the crop modal --}}
                    <input type="file" name="photo" id="photo" class="hidden" required>

                    <button type="button"
                            onclick="document.getElementById('photoChooser').click()"
                            class="px-3 py-1.5 border border-gray-300 dark:border-neutral-600 rounded-md text-sm font-medium hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors dark:text-neutral-200">
                        Choose Photo
                    </button>
                    <input type="file"
                           id="photoChooser"
                           accept="image/png, image/jpeg, image/webp"
                           class="hidden">

                    @error('photo')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500 dark:text-neutral-400">JPG, JPEG, PNG or WEBP. Max 2MB. You'll be asked to crop it to a square.</p>

                    <button type="submit"
                            id="photoSubmit"
                            disabled
                            class="px-3 py-1.5 bg-blue-900 text-white rounded-md text-sm font-medium hover:bg-blue-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Upload Photo
                    </button>
                </form>
            </div>
        </div>

        {{-- crop modal --}}
        <div id="cropModal" class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-neutral-800 rounded-md shadow-lg w-full max-w-lg p-4 space-y-4">
                <h3 class="font-semibold text-gray-900 dark:text-neutral-100">Crop Your Photo</h3>

                <div class="max-h-[60vh] overflow-hidden">
                    <img id="cropImage" class="max-w-full block">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCropModal()"
                            class="px-3 py-1.5 border border-gray-300 dark:border-neutral-600 rounded-md text-sm font-medium hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors dark:text-neutral-200">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmCrop()"
                            class="px-3 py-1.5 bg-blue-900 text-white rounded-md text-sm font-medium hover:bg-blue-800 transition-colors">
                        Crop Photo
                    </button>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
        <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
        <script>
            let cropper = null;
            let selectedFileName = 'profile.jpg';

            const photoChooser = document.getElementById('photoChooser');
            const cropImage = document.getElementById('cropImage');
            const cropModal = document.getElementById('cropModal');
            const photoInput = document.getElementById('photo');
            const photoSubmit = document.getElementById('photoSubmit');
            const photoPreview = document.getElementById('photoPreview');
            const photoPlaceholder = document.getElementById('photoPlaceholder');

            photoChooser.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) {
                    return;
                }

                selectedFileName = file.name;

                const reader = new FileReader();
                reader.onload = function (e) {
                    cropImage.src = e.target.result;
                    cropModal.classList.remove('hidden');

                    cropper?.destroy();
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                    });
                };
                reader.readAsDataURL(file);

                // allow re-selecting the same file later
                this.value = '';
            });

            function closeCropModal() {
                cropper?.destroy();
                cropper = null;
                cropModal.classList.add('hidden');
            }

            function confirmCrop() {
                if (!cropper) {
                    return;
                }

                cropper.getCroppedCanvas({ width: 512, height: 512 }).toBlob(function (blob) {
                    const croppedFile = new File([blob], selectedFileName, { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    photoInput.files = dataTransfer.files;

                    const previewUrl = URL.createObjectURL(blob);
                    photoPreview.src = previewUrl;
                    photoPreview.classList.remove('hidden');
                    photoPlaceholder.classList.add('hidden');

                    photoSubmit.disabled = false;
                    closeCropModal();
                }, 'image/jpeg', 0.9);
            }
        </script>


        {{-- account details (read-only) --}}
        <div class="bg-white dark:bg-neutral-800 border border-neutral-400 dark:border-neutral-700 rounded-md p-4 md:p-6 shadow-sm">
            <h2 class="font-semibold text-base md:text-lg text-blue-900 dark:text-blue-400 mb-4">Account Details</h2>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-500 dark:text-neutral-400">First Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->first_name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-neutral-400">Middle Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->middle_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-neutral-400">Last Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-neutral-400">Suffix</dt>
                    <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->suffix ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-neutral-400">Email</dt>
                    <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-neutral-400">Role</dt>
                    <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ ucfirst($user->role) }}</dd>
                </div>
                @if($user->isStudent())
                    <div>
                        <dt class="text-gray-500 dark:text-neutral-400">LRN</dt>
                        <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->lrn ?? '—' }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- role-specific details (read-only) --}}
        @if($user->isTeacher() && $user->teacher)
            <div class="bg-white dark:bg-neutral-800 border border-neutral-400 dark:border-neutral-700 rounded-md p-4 md:p-6 shadow-sm">
                <h2 class="font-semibold text-base md:text-lg text-blue-900 dark:text-blue-400 mb-4">Teacher Details</h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-neutral-400">Employee ID</dt>
                        <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->teacher->employee_id ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-neutral-400">Department</dt>
                        <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->teacher->department_id ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-neutral-400">Hire Date</dt>
                        <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->teacher->hire_date ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        @endif

        @if($user->isStudent() && $user->student)
            <div class="bg-white dark:bg-neutral-800 border border-neutral-400 dark:border-neutral-700 rounded-md p-4 md:p-6 shadow-sm">
                <h2 class="font-semibold text-base md:text-lg text-blue-900 dark:text-blue-400 mb-4">Student Details</h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-neutral-400">Grade Level</dt>
                        <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ $user->student->grade_level ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-neutral-400">Enrollment Status</dt>
                        <dd class="font-medium text-gray-900 dark:text-neutral-100">{{ ucfirst($user->student->enrollment_status ?? '—') }}</dd>
                    </div>
                </dl>
            </div>
        @endif

    </div>
</x-layouts.app>