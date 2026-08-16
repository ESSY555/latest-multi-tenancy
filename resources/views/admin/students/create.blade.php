@extends('layouts.dashboard')

@section('title', 'Add New Student')

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Add New Student</h1>
                        <p class="text-gray-600 mt-2">Create a new student account</p>
                    </div>
                    <a href="{{ route('admin.students.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Back to Students
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <p class="font-semibold mb-1">Could not create student. Please fix the errors below.</p>
                            @if($errors->has('error'))
                                <p class="text-sm">{{ $errors->first('error') }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text"
                                   name="username"
                                   id="username"
                                   value="{{ old('username') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="student123">
                            <p class="mt-1 text-xs text-gray-500">Set username or email (at least one is required).</p>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="student@example.com">
                            <p class="mt-1 text-xs text-gray-500">Set username or email (at least one is required).</p>
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
                                   value="{{ old('phone') }}"
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
                                    required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                    <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
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
                                    required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}"
                                            data-branch-id="{{ $class->branch_id }}"
                                            {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
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
                                    required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                {{-- <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Other</option> --}}
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
                                   value="{{ old('date_of_birth') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                            <input type="text"
                                   name="nationality"
                                   id="nationality"
                                   value="{{ old('nationality') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="state_of_origin" class="block text-sm font-medium text-gray-700 mb-2">State of Origin</label>
                            <input type="text"
                                   name="state_of_origin"
                                   id="state_of_origin"
                                   value="{{ old('state_of_origin') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="local_government_area" class="block text-sm font-medium text-gray-700 mb-2">Local Government Area</label>
                            <input type="text"
                                   name="local_government_area"
                                   id="local_government_area"
                                   value="{{ old('local_government_area') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text"
                                   name="religion"
                                   id="religion"
                                   value="{{ old('religion') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="church_denomination" class="block text-sm font-medium text-gray-700 mb-2">Church Denomination</label>
                            <input type="text"
                                   name="church_denomination"
                                   id="church_denomination"
                                   value="{{ old('church_denomination') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="residential_address" class="block text-sm font-medium text-gray-700 mb-2">Residential Address (Student)</label>
                            <textarea name="residential_address"
                                      id="residential_address"
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('residential_address') }}</textarea>
                        </div>

                        <div>
                            <label for="language_of_communication" class="block text-sm font-medium text-gray-700 mb-2">Language of Communication</label>
                            <input type="text"
                                   name="language_of_communication"
                                   id="language_of_communication"
                                   value="{{ old('language_of_communication') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="number_of_children_in_family" class="block text-sm font-medium text-gray-700 mb-2">No. of Children in Family</label>
                            <input type="number"
                                   min="1"
                                   name="number_of_children_in_family"
                                   id="number_of_children_in_family"
                                   value="{{ old('number_of_children_in_family') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="position_in_family" class="block text-sm font-medium text-gray-700 mb-2">Position in Family</label>
                            <input type="number"
                                   min="1"
                                   name="position_in_family"
                                   id="position_in_family"
                                   value="{{ old('position_in_family') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="school_last_attended" class="block text-sm font-medium text-gray-700 mb-2">School Last Attended</label>
                            <input type="text"
                                   name="school_last_attended"
                                   id="school_last_attended"
                                   value="{{ old('school_last_attended') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="class_last_attended" class="block text-sm font-medium text-gray-700 mb-2">Class Last Attended</label>
                            <input type="text"
                                   name="class_last_attended"
                                   id="class_last_attended"
                                   value="{{ old('class_last_attended') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Any Health Challenges?</label>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="has_health_challenges" value="1" {{ old('has_health_challenges') == '1' ? 'checked' : '' }} class="mr-2">
                                    Yes
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="has_health_challenges" value="0" {{ old('has_health_challenges', '0') == '0' ? 'checked' : '' }} class="mr-2">
                                    No
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="health_challenges_details" class="block text-sm font-medium text-gray-700 mb-2">If yes, state details</label>
                            <textarea name="health_challenges_details"
                                      id="health_challenges_details"
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('health_challenges_details') }}</textarea>
                        </div>

                        <div>
                            <label for="crisis_response" class="block text-sm font-medium text-gray-700 mb-2">What should be done in a crisis?</label>
                            <select name="crisis_response"
                                    id="crisis_response"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select response</option>
                                <option value="administer_first_aid" {{ old('crisis_response') == 'administer_first_aid' ? 'selected' : '' }}>Administer first aid</option>
                                <option value="do_not_administer_first_aid" {{ old('crisis_response') == 'do_not_administer_first_aid' ? 'selected' : '' }}>Do not administer first aid</option>
                                <option value="call_parents_guardians" {{ old('crisis_response') == 'call_parents_guardians' ? 'selected' : '' }}>Call parents/guardians</option>
                            </select>
                        </div>

                        <!-- Parent Information Section -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-200 pb-2">Parent/Guardian Information</h3>
                        </div>

                        <!-- Parent Name -->
                        <div>
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian Name *</label>
                            <input type="text" 
                                   name="parent_name" 
                                   id="parent_name" 
                                   value="{{ old('parent_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
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
                                   value="{{ old('parent_phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('parent_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Email -->
                        <div>
                            <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-2">Parent Email</label>
                            <input type="email" 
                                   name="parent_email" 
                                   id="parent_email" 
                                   value="{{ old('parent_email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('parent_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="father_name" class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
                            <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-2">Mother's Name</label>
                            <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="father_phone_number" class="block text-sm font-medium text-gray-700 mb-2">Father's Phone</label>
                            <input type="tel" name="father_phone_number" id="father_phone_number" value="{{ old('father_phone_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="mother_phone_number" class="block text-sm font-medium text-gray-700 mb-2">Mother's Phone</label>
                            <input type="tel" name="mother_phone_number" id="mother_phone_number" value="{{ old('mother_phone_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="father_occupation" class="block text-sm font-medium text-gray-700 mb-2">Father's Occupation</label>
                            <input type="text" name="father_occupation" id="father_occupation" value="{{ old('father_occupation') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="mother_occupation" class="block text-sm font-medium text-gray-700 mb-2">Mother's Occupation</label>
                            <input type="text" name="mother_occupation" id="mother_occupation" value="{{ old('mother_occupation') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="father_residential_address" class="block text-sm font-medium text-gray-700 mb-2">Father's Residential Address</label>
                            <textarea name="father_residential_address" id="father_residential_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('father_residential_address') }}</textarea>
                        </div>

                        <div>
                            <label for="mother_residential_address" class="block text-sm font-medium text-gray-700 mb-2">Mother's Residential Address</label>
                            <textarea name="mother_residential_address" id="mother_residential_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('mother_residential_address') }}</textarea>
                        </div>

                        <div>
                            <label for="father_office_address" class="block text-sm font-medium text-gray-700 mb-2">Father's Office Address</label>
                            <textarea name="father_office_address" id="father_office_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('father_office_address') }}</textarea>
                        </div>

                        <div>
                            <label for="mother_office_address" class="block text-sm font-medium text-gray-700 mb-2">Mother's Office Address</label>
                            <textarea name="mother_office_address" id="mother_office_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('mother_office_address') }}</textarea>
                        </div>

                        <!-- Profile Photo -->
                        <div class="md:col-span-2">
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Student Portrait Passport</label>

                            <div class="mt-1 space-y-4">
                                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                                    <div id="upload-section" class="flex-1">
                                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all">
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG or WEBP up to 2MB. Recommended ratio: 4:5 (Passport
                                            style)</p>
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
                                    <img id="photo-preview-image" class="hidden w-full h-full object-contain" />
                                    <svg id="photo-placeholder-icon" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        </div>
                            </div>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <h3 class="text-sm font-semibold text-blue-900 mb-3">Student Login Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-blue-800">Use at least 8 characters.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Create Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const branchSelect = document.getElementById('branch_id');
            const classSelect = document.getElementById('school_class_id');
            if (branchSelect && classSelect) {
                const placeholderOption = classSelect.querySelector('option[value=""]');
                const allClassOptions = Array.from(classSelect.querySelectorAll('option[data-branch-id]'));

                function filterClassesByBranch() {
                    const selectedBranchId = branchSelect.value;
                    const currentClassId = classSelect.value;

                    classSelect.innerHTML = '';
                    if (placeholderOption) {
                        classSelect.appendChild(placeholderOption);
                    }

                    allClassOptions.forEach(option => {
                        if (!selectedBranchId || option.dataset.branchId === selectedBranchId) {
                            classSelect.appendChild(option);
                        }
                    });

                    const hasCurrentOption = Array.from(classSelect.options).some(option => option.value === currentClassId);
                    classSelect.value = hasCurrentOption ? currentClassId : '';
                }

                branchSelect.addEventListener('change', filterClassesByBranch);
                filterClassesByBranch();
            }

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
        })();
    </script>
@endpush

