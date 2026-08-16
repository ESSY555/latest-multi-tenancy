                                    @extends('layouts.dashboard')

@section('title', 'Edit Student - ' . $student->name)

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Student</h1>
                        <p class="text-gray-600 mt-2">Update student information for {{ $student->name }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.students.show', $student) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            View Profile
                        </a>
                        <a href="{{ route('admin.students.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Back to Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $student->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="md:col-span-2">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   value="{{ old('username', $student->username) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="student123">
                            <p class="mt-1 text-xs text-gray-500">Set username or email (at least one is required).</p>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $student->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $student->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Branch -->
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                            <select name="branch_id" 
                                    id="branch_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    >
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" 
                                        {{ old('branch_id', $studentBranch->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Academy Section/Year -->
                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">Academy Section/Year *</label>
                            <select name="academic_year_id" 
                                    id="academic_year_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Select Academy Section/Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $student->studentProfile->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class -->
                        <div>
                            <label for="school_class_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                            <select name="school_class_id" 
                                    id="school_class_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    >
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}"
                                        data-branch-id="{{ $class->branch_id }}"
                                        {{ old('school_class_id', $selectedClassId) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_class_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select name="gender" 
                                    id="gender" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    >
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $student->studentProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->studentProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $student->studentProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                            <input type="date" 
                                   name="date_of_birth" 
                                   id="date_of_birth" 
                                   value="{{ old('date_of_birth', $student->studentProfile->date_of_birth ? $student->studentProfile->date_of_birth->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   >
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      >{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Information Section -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Parent/Guardian Information</h3>
                        </div>

                        <!-- Parent Name -->
                        <div class="md:col-span-2">
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian Name *</label>
                            <input type="text" 
                                   name="parent_name" 
                                   id="parent_name" 
                                   value="{{ old('parent_name', $student->studentProfile->parent_name ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   >
                            @error('parent_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Phone -->
                        <div>
                            <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-2">Parent Phone</label>
                            <input type="tel" 
                                   name="parent_phone" 
                                   id="parent_phone" 
                                   value="{{ old('parent_phone', $student->studentProfile->parent_phone ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   >
                            @error('parent_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Email -->
                        <div>
                            <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-2">Parent Email *</label>
                            <input type="email" 
                                   name="parent_email" 
                                   id="parent_email" 
                                   value="{{ old('parent_email', $student->studentProfile->parent_email ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   >
                            @error('parent_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div class="md:col-span-2">
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Student Portrait Passport</label>

                            <div class="mt-1 space-y-4">
                                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                                    <div id="upload-section" class="flex-1">
                                        <input type="file" 
                                               name="profile_photo" 
                                               id="profile_photo" 
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all">
                                        <p class="mt-1 text-xs text-gray-500">
                                            @if($student->profile_photo)
                                                Upload a new photo to replace the current one.
                                            @else
                                                PNG, JPG, JPEG or WEBP up to 2MB. Recommended ratio: 4:5.
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap gap-3 shrink-0">
                                        <button type="button" id="btn-upload-mode"
                                            class="px-4 py-2 bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition-colors text-sm font-medium border border-green-200 hidden">Upload
                                            Image</button>
                                        <button type="button" id="btn-camera-mode"
                                            class="px-4 py-2 bg-gray-50 text-gray-700 rounded-md hover:bg-gray-100 transition-colors text-sm font-medium border border-gray-200 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Use Camera
                                        </button>
                                    </div>
                                </div>

                                <div id="camera-section" class="hidden">
                                    <div
                                        class="relative w-full max-w-sm aspect-[4/5] bg-black rounded-lg overflow-hidden border-2 border-gray-200 mb-3 mx-auto">
                                        <video id="camera-stream" autoplay playsinline class="w-full h-full object-cover"></video>
                                        <canvas id="camera-canvas" class="hidden"></canvas>
                                    </div>
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" id="btn-capture"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Capture
                                            Photo</button>
                                        <button type="button" id="btn-retake"
                                            class="hidden px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors text-sm font-medium">Retake</button>
                                        <button type="button" id="btn-switch-camera"
                                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors text-sm font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Switch</button>
                                    </div>
                                    <p class="mt-2 text-xs text-red-500 hidden text-center" id="camera-error"></p>
                                </div>

                                <div class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50 overflow-hidden"
                                    id="photo-preview-container">
                                    @if($student->profile_photo)
                                        <img id="photo-preview-image" src="{{ asset('uploads/profile-photos/' . $student->profile_photo) }}" class="w-full h-full object-contain" />
                                        <svg id="photo-placeholder-icon" class="hidden h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <img id="photo-preview-image" class="hidden w-full h-full object-contain" />
                                        <svg id="photo-placeholder-icon" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Update Section -->
                    <div class="mt-6 bg-white border border-gray-200 rounded-md p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Update Password (Optional)</h3>
                        <p class="text-sm text-gray-500 mb-4">Leave these fields blank if you do not want to change the student's password.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent pr-10">
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePasswordVisibility('password', this)">
                                        <svg class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent pr-10">
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePasswordVisibility('password_confirmation', this)">
                                        <svg class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('.eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const branchSelect = document.getElementById('branch_id');
            const classSelect = document.getElementById('school_class_id');
            if (!branchSelect || !classSelect) return;

            const allClassOptions = Array.from(classSelect.querySelectorAll('option[data-branch-id]')).map((option) => ({
                value: option.value,
                label: option.textContent.trim(),
                branchId: option.getAttribute('data-branch-id')
            }));

            function refreshClassOptions() {
                const selectedBranchId = branchSelect.value;
                const previousValue = classSelect.value;

                classSelect.innerHTML = '<option value="">Select Class</option>';

                const matchingOptions = allClassOptions.filter((option) => option.branchId === selectedBranchId);
                matchingOptions.forEach((option) => {
                    const node = document.createElement('option');
                    node.value = option.value;
                    node.textContent = option.label;
                    classSelect.appendChild(node);
                });

                if (matchingOptions.some((option) => option.value === previousValue)) {
                    classSelect.value = previousValue;
                }
            }

            branchSelect.addEventListener('change', refreshClassOptions);
            refreshClassOptions();

            // Camera & Upload Logic
            const btnUploadMode = document.getElementById('btn-upload-mode');
            const btnCameraMode = document.getElementById('btn-camera-mode');
            const uploadSection = document.getElementById('upload-section');
            const cameraSection = document.getElementById('camera-section');
            const video = document.getElementById('camera-stream');
            const canvas = document.getElementById('camera-canvas');
            const btnCapture = document.getElementById('btn-capture');
            const btnRetake = document.getElementById('btn-retake');
            const btnSwitchCamera = document.getElementById('btn-switch-camera');
            const photoInput = document.getElementById('profile_photo');
            const previewImage = document.getElementById('photo-preview-image');
            const placeholderIcon = document.getElementById('photo-placeholder-icon');
            const cameraError = document.getElementById('camera-error');
            let stream = null;
            let currentFacingMode = 'user';

            if (btnUploadMode && btnCameraMode) {
                btnUploadMode.addEventListener('click', () => {
                    uploadSection.classList.remove('hidden');
                    cameraSection.classList.add('hidden');
                    btnUploadMode.classList.replace('bg-gray-50', 'bg-green-50');
                    btnUploadMode.classList.replace('text-gray-700', 'text-green-700');
                    btnUploadMode.classList.replace('border-gray-200', 'border-green-200');

                    btnCameraMode.classList.replace('bg-blue-50', 'bg-gray-50');
                    btnCameraMode.classList.replace('text-blue-700', 'text-gray-700');
                    btnCameraMode.classList.replace('border-blue-200', 'border-gray-200');
                    stopCamera();
                });

                btnCameraMode.addEventListener('click', async () => {
                    uploadSection.classList.add('hidden');
                    cameraSection.classList.remove('hidden');

                    btnCameraMode.classList.replace('bg-gray-50', 'bg-blue-50');
                    btnCameraMode.classList.replace('text-gray-700', 'text-blue-700');
                    btnCameraMode.classList.replace('border-gray-200', 'border-blue-200');

                    btnUploadMode.classList.replace('bg-green-50', 'bg-gray-50');
                    btnUploadMode.classList.replace('text-green-700', 'text-gray-700');
                    btnUploadMode.classList.replace('border-green-200', 'border-gray-200');

                    await startCamera();
                });

                async function startCamera() {
                    try {
                        cameraError.classList.add('hidden');
                        if (stream) {
                            stopCamera();
                        }
                        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: currentFacingMode }, audio: false });
                        video.srcObject = stream;
                        video.play();
                        btnCapture.classList.remove('hidden');
                        btnRetake.classList.add('hidden');
                        if (btnSwitchCamera) btnSwitchCamera.classList.remove('hidden');
                    } catch (err) {
                        console.error("Error accessing camera:", err);
                        cameraError.textContent = "Could not access camera. Please allow permissions in your browser.";
                        cameraError.classList.remove('hidden');
                    }
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                }

                btnCapture.addEventListener('click', () => {
                    canvas.width = video.videoWidth || 480;
                    canvas.height = video.videoHeight || 640;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Convert to file with enhanced error handling
                    canvas.toBlob(blob => {
                        if (!blob) {
                            console.error('Canvas toBlob returned null');
                            cameraError.textContent = "Failed to capture image. Please try again.";
                            cameraError.classList.remove('hidden');
                            return;
                        }

                        const file = new File([blob], "camera_capture.jpg", { type: "image/jpeg" });

                        // Validate file size
                        if (file.size === 0) {
                            console.error('Captured file is empty');
                            cameraError.textContent = "Captured image is empty. Please try again.";
                            cameraError.classList.remove('hidden');
                            return;
                        }

                        // Use DataTransfer to set file input value
                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            photoInput.files = dataTransfer.files;

                            // Validate file was actually set
                            if (!photoInput.files || photoInput.files.length === 0) {
                                console.error('Failed to set file input - falling back to blob URL');
                                // Fallback: show preview but warn user
                                cameraError.textContent = "Note: File input may not be supported in this browser. Ensure camera capture is enabled.";
                                cameraError.classList.remove('hidden');
                            }
                        } catch (err) {
                            console.error('DataTransfer error:', err);
                            cameraError.textContent = "Error setting file input. Your browser may not support this feature.";
                            cameraError.classList.remove('hidden');
                            return;
                        }

                        // Show preview
                        const url = URL.createObjectURL(blob);
                        previewImage.src = url;
                        previewImage.classList.remove('hidden');
                        placeholderIcon.classList.add('hidden');
                        cameraError.classList.add('hidden');

                        // Pause video
                        video.pause();
                        btnCapture.classList.add('hidden');
                        btnRetake.classList.remove('hidden');
                        if (btnSwitchCamera) btnSwitchCamera.classList.add('hidden');

                        console.info('Camera capture successful', { size: file.size, type: file.type });
                    }, 'image/jpeg', 0.9);
                });

                btnRetake.addEventListener('click', () => {
                    video.play();
                    btnCapture.classList.remove('hidden');
                    btnRetake.classList.add('hidden');
                    if (btnSwitchCamera) btnSwitchCamera.classList.remove('hidden');
                });

                if (btnSwitchCamera) {
                    btnSwitchCamera.addEventListener('click', async () => {
                        currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                        await startCamera();
                    });
                }

                // Also preview file upload
                photoInput.addEventListener('change', (e) => {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            previewImage.src = evt.target.result;
                            previewImage.classList.remove('hidden');
                            placeholderIcon.classList.add('hidden');
                        }
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });

                // Stop camera if navigating away
                window.addEventListener('beforeunload', stopCamera);
            }
        });
    </script>
@endsection

