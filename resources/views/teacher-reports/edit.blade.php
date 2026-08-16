@extends('layouts.dashboard')

@section('title', 'Edit Teacher Report')

@section('dashboard')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Edit Teacher Report</h1>
        <a href="{{ route('teacher-reports.show', $teacherReport) }}" class="px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Back to Report</a>
    </div>
    
    <div class="bg-white rounded shadow p-6">
        <p class="text-gray-600 mb-6">Update the details of your teaching activities for {{ $teacherReport->formatted_report_date }}.</p>

        <!-- Form -->
        <form action="{{ route('teacher-reports.update', $teacherReport) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teacher Name -->
                    <div>
                        <label for="teacher_name" class="block text-sm font-medium text-gray-700 mb-2">Teacher's Name</label>
                        <input type="text" 
                               id="teacher_name" 
                               name="teacher_name" 
                               value="{{ old('teacher_name', $teacherReport->teacher_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('teacher_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Report Date -->
                    <div>
                        <label for="report_date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" 
                               id="report_date" 
                               name="report_date" 
                               value="{{ old('report_date', $teacherReport->report_date->format('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error('report_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Class & Subject Details Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Class & Subject Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Classes Taught -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Classes Taught</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($classes as $class)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="classes_taught[]" 
                                           value="{{ $class->id }}" 
                                           {{ in_array($class->id, old('classes_taught', $teacherReport->classes_taught ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ $class->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Select all classes you taught today</p>
                        @error('classes_taught')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subjects Taught -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Subjects Taught</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($subjects as $subject)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="subjects_taught[]" 
                                           value="{{ $subject->id }}" 
                                           {{ in_array($subject->id, old('subjects_taught', $teacherReport->subjects_taught ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Select all subjects you taught today</p>
                        @error('subjects_taught')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Topics Covered -->
                <div class="mt-6">
                    <label for="topics_covered" class="block text-sm font-medium text-gray-700 mb-2">Topics Covered</label>
                    <textarea id="topics_covered" 
                              name="topics_covered" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Describe the topics covered in today's lessons..."
                              required>{{ old('topics_covered', $teacherReport->topics_covered) }}</textarea>
                    @error('topics_covered')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Lesson & Progress Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Lesson & Progress</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teaching Method -->
                    <div>
                        <label for="teaching_method" class="block text-sm font-medium text-gray-700 mb-2">Teaching Method Used</label>
                        <select id="teaching_method" 
                                name="teaching_method" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a method (optional)</option>
                            @foreach($teachingMethods as $key => $method)
                                <option value="{{ $key }}" 
                                        {{ old('teaching_method', $teacherReport->teaching_method) == $key ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                        @error('teaching_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Participation -->
                    <div>
                        <label for="student_participation" class="block text-sm font-medium text-gray-700 mb-2">Student Participation/Engagement</label>
                        <select id="student_participation" 
                                name="student_participation" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select participation level (optional)</option>
                            @foreach($participationLevels as $key => $level)
                                <option value="{{ $key }}" 
                                        {{ old('student_participation', $teacherReport->student_participation) == $key ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_participation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Objectives Achieved -->
                <div class="mt-6">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" 
                               id="objectives_achieved" 
                               name="objectives_achieved" 
                               value="1" 
                               {{ old('objectives_achieved', $teacherReport->objectives_achieved) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="objectives_achieved" class="ml-2 text-sm font-medium text-gray-700">Lesson objectives achieved?</label>
                    </div>
                    <textarea id="objectives_notes" 
                              name="objectives_notes" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Additional notes about objectives achievement...">{{ old('objectives_notes', $teacherReport->objectives_notes) }}</textarea>
                    @error('objectives_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Participation Notes -->
                <div class="mt-4">
                    <label for="participation_notes" class="block text-sm font-medium text-gray-700 mb-2">Participation Notes</label>
                    <textarea id="participation_notes" 
                              name="participation_notes" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Additional notes about student participation...">{{ old('participation_notes', $teacherReport->participation_notes) }}</textarea>
                    @error('participation_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Assignments & Activities Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Assignments & Activities</h2>
                
                <!-- Homework Assigned -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" 
                               id="homework_assigned" 
                               name="homework_assigned" 
                               value="1" 
                               {{ old('homework_assigned', $teacherReport->homework_assigned) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="homework_assigned" class="ml-2 text-sm font-medium text-gray-700">Homework/Assignment given?</label>
                    </div>
                    <textarea id="homework_details" 
                              name="homework_details" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Details about the homework or assignment...">{{ old('homework_details', $teacherReport->homework_details) }}</textarea>
                    @error('homework_details')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Activities -->
                <div>
                    <label for="class_activities" class="block text-sm font-medium text-gray-700 mb-2">Class Activities Done</label>
                    <textarea id="class_activities" 
                              name="class_activities" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Describe any class activities conducted...">{{ old('class_activities', $teacherReport->class_activities) }}</textarea>
                    @error('class_activities')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Challenges & Needs Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Challenges & Needs</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Challenges Faced -->
                    <div>
                        <label for="challenges_faced" class="block text-sm font-medium text-gray-700 mb-2">Challenges Faced</label>
                        <textarea id="challenges_faced" 
                                  name="challenges_faced" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any challenges encountered during teaching...">{{ old('challenges_faced', $teacherReport->challenges_faced) }}</textarea>
                        @error('challenges_faced')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Materials Needed -->
                    <div>
                        <label for="materials_needed" class="block text-sm font-medium text-gray-700 mb-2">Materials/Resources Needed</label>
                        <textarea id="materials_needed" 
                                  name="materials_needed" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any materials or resources needed...">{{ old('materials_needed', $teacherReport->materials_needed) }}</textarea>
                        @error('materials_needed')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Notes Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Additional Notes</h2>
                
                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">Any Other Remarks</label>
                    <textarea id="additional_notes" 
                              name="additional_notes" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Any additional comments or observations...">{{ old('additional_notes', $teacherReport->additional_notes) }}</textarea>
                    @error('additional_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('teacher-reports.show', $teacherReport) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 cursor-pointer">
                    Update Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

