@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('dashboard')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Enhanced Header -->
            <div class="mb-10">
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">My Profile</h1>
                            <p class="mt-2 text-lg text-gray-600">Manage your personal information, profile photo, and account settings</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 rounded-lg p-6 shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-800">Success!</h3>
                                <p class="text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Enhanced Profile Photo Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                        <div class="text-center">
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Profile Photo</h3>
                                <div class="w-16 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto rounded-full"></div>
                            </div>
                            
                            <div class="relative mx-auto h-40 w-40 mb-6">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-1">
                                    <div class="h-full w-full rounded-full overflow-hidden bg-white">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('uploads/profile-photos/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">To update your profile photo, please contact your class teacher or the school administrator.</p>
                            </div>
                    </div>
                </div>
            </div>

                <!-- Enhanced Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Personal Information</h3>
                            </div>
                            <div class="w-20 h-1 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
                        </div>
                    
                        <div class="space-y-6">
                            <fieldset disabled class="opacity-80">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-500 @enderror">
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('phone') border-red-500 @enderror">
                                    </div>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="space-y-2">
                                <label for="address" class="block text-sm font-semibold text-gray-700">Address</label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <textarea name="address" id="address" rows="3" 
                                              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                                </div>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="bio" class="block text-sm font-semibold text-gray-700">Bio</label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <textarea name="bio" id="bio" rows="3" placeholder="Tell us about yourself..." 
                                              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('bio') border-red-500 @enderror">{{ old('bio', $user->bio) }}</textarea>
                                </div>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            </fieldset>
                        </div>
                </div>

                    <!-- Enhanced Emergency Contact -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="h-10 w-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Emergency Contact</h3>
                            </div>
                            <div class="w-20 h-1 bg-gradient-to-r from-red-500 to-pink-600 rounded-full"></div>
                        </div>
                    
                        <div class="space-y-6">
                            <fieldset disabled class="opacity-80">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="emergency_contact_name" class="block text-sm font-semibold text-gray-700">Contact Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                                               value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('emergency_contact_name') border-red-500 @enderror">
                                    </div>
                                    @error('emergency_contact_name')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="emergency_contact_phone" class="block text-sm font-semibold text-gray-700">Contact Phone</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" 
                                               value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('emergency_contact_phone') border-red-500 @enderror">
                                    </div>
                                    @error('emergency_contact_phone')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="space-y-2">
                                <label for="emergency_contact_relationship" class="block text-sm font-semibold text-gray-700">Relationship</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <select name="emergency_contact_relationship" id="emergency_contact_relationship" 
                                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('emergency_contact_relationship') border-red-500 @enderror">
                                        <option value="">Select relationship</option>
                                        <option value="Parent" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship) == 'Parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="Guardian" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship) == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                        <option value="Sibling" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship) == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                        <option value="Relative" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship) == 'Relative' ? 'selected' : '' }}>Relative</option>
                                        <option value="Friend" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship) == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Other" {{ old('emergency_contact_relationship', $user->emergency_contact_relationship) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                @error('emergency_contact_relationship')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            </fieldset>
                        </div>
                </div>

                    <!-- Password Change (Disabled for Students) -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="h-10 w-10 bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Change Password</h3>
                            </div>
                            <div class="w-20 h-1 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full"></div>
                        </div>
                    
                        <div class="text-center py-6 text-gray-500">
                            <p>Password changes are disabled for student accounts. Please contact your school administrator or teacher if you need to reset your password.</p>
                        </div>
                    </div>

                    <!-- Enhanced Notification Preferences -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="h-10 w-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4.828 17H12l-2.586 2.586a2 2 0 01-2.828 0L4.828 17z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Notification Preferences</h3>
                            </div>
                            <div class="w-20 h-1 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full"></div>
                        </div>
                    
                        <form method="POST" action="{{ route('student.profile.notifications') }}" class="space-y-6">
                            @csrf
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="email_notifications" class="text-lg font-semibold text-gray-900">Email Notifications</label>
                                                <p class="text-sm text-gray-600">Receive general email notifications</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="email_notifications" id="email_notifications" value="1" 
                                                   {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="assignment_reminders" class="text-lg font-semibold text-gray-900">Assignment Reminders</label>
                                                <p class="text-sm text-gray-600">Get reminded about upcoming assignments</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="assignment_reminders" id="assignment_reminders" value="1" 
                                                   {{ old('assignment_reminders', $user->assignment_reminders) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="grade_notifications" class="text-lg font-semibold text-gray-900">Grade Notifications</label>
                                                <p class="text-sm text-gray-600">Get notified when new grades are posted</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="grade_notifications" id="grade_notifications" value="1" 
                                                   {{ old('grade_notifications', $user->grade_notifications) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-600"></div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.847 9.168-4.5a1.5 1.5 0 00-1.416-2.5C14.5 1 12.1 2.5 9.5 2.5c-2.1 0-4.1.5-5.5 1.5"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="announcement_notifications" class="text-lg font-semibold text-gray-900">Announcement Notifications</label>
                                                <p class="text-sm text-gray-600">Get notified about new class announcements</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="announcement_notifications" id="announcement_notifications" value="1" 
                                                   {{ old('announcement_notifications', $user->announcement_notifications) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 border border-red-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="attendance_alerts" class="text-lg font-semibold text-gray-900">Attendance Alerts</label>
                                                <p class="text-sm text-gray-600">Get alerts about attendance issues</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="attendance_alerts" id="attendance_alerts" value="1" 
                                                   {{ old('attendance_alerts', $user->attendance_alerts) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-6">
                                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Update Preferences
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Profile Photo Form -->
<form id="delete-photo-form" method="POST" action="{{ route('student.profile.photo.delete') }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteProfilePhoto() {
    if (confirm('Are you sure you want to delete your profile photo?')) {
        document.getElementById('delete-photo-form').submit();
    }
}

function previewImage(input) {
    const file = input.files[0];
    const uploadArea = document.getElementById('upload-area');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            input.value = '';
            return;
        }
        
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadArea.classList.add('hidden');
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        // Reset to upload area if no file selected
        uploadArea.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        previewImg.src = '';
    }
}

// Reset preview when clicking on the upload area
document.getElementById('profile-photo-input').addEventListener('click', function() {
    const uploadArea = document.getElementById('upload-area');
    const imagePreview = document.getElementById('image-preview');
    
    // If preview is showing, reset to upload area
    if (!imagePreview.classList.contains('hidden')) {
        uploadArea.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        this.value = ''; // Clear the file input
    }
});
</script>
@endsection

