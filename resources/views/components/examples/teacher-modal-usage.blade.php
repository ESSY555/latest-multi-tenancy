{{-- Example: How to use the reusable Teacher Details Modal across different pages --}}

{{-- 
    This file demonstrates how to use the reusable teacher details modal component
    across different pages in your application.
    
    Simply include this component wherever you need to show teacher details:
--}}

{{-- Include the reusable modal components --}}
<x-modals.teacher-details-modal />
<x-modals.teacher-edit-modal />

{{-- Example table with teacher data --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6">Teachers List (Example)</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-lg">J</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">John Doe</div>
                                <div class="text-sm text-gray-500">Mathematics Teacher</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">john.doe@example.com</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        {{-- 
                            Both modals work seamlessly together.
                            Just call the functions with the teacher ID.
                        --}}
                        <button onclick="viewTeacherDetails(1)" class="text-blue-600 hover:text-blue-900 mr-3">
                            View Details
                        </button>
                        <button onclick="editTeacher(1)" class="text-indigo-600 hover:text-indigo-900">
                            Edit
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 font-semibold text-lg">S</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Sarah Smith</div>
                                <div class="text-sm text-gray-500">Science Teacher</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">sarah.smith@example.com</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewTeacherDetails(2)" class="text-blue-600 hover:text-blue-900 mr-3">
                            View Details
                        </button>
                        <button onclick="editTeacher(2)" class="text-indigo-600 hover:text-indigo-900">
                            Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- 
    BENEFITS OF USING THE REUSABLE COMPONENTS:
    
    1. CONSISTENT DESIGN: The same beautiful modals appear across all pages
    2. EASY MAINTENANCE: Update modal designs in one place, affects all pages
    3. CODE REUSABILITY: No need to duplicate modal HTML/CSS/JS
    4. AUTOMATIC FUNCTIONALITY: All modal behaviors (close on outside click, escape key, etc.) work automatically
    5. RESPONSIVE DESIGN: Mobile-friendly design included automatically
    6. PROFESSIONAL LOOK: Beautiful gradients, animations, and hover effects everywhere
    7. SEAMLESS INTEGRATION: Both view and edit modals work together perfectly
    
    USAGE INSTRUCTIONS:
    
    1. Include both components: 
       <x-modals.teacher-details-modal />
       <x-modals.teacher-edit-modal />
    2. Call the functions: 
       - viewTeacherDetails(teacherId) - for viewing teacher details
       - editTeacher(teacherId) - for editing teacher information
    3. That's it! Both modals will work perfectly on any page.
    
    PAGES WHERE YOU CAN USE THIS:
    
    - Dashboard overview
    - Teacher listing pages
    - Class management pages
    - Subject management pages
    - Reports and analytics pages
    - Any page that needs to show or edit teacher information
--}}

