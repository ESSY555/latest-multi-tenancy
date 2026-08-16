@extends('layouts.dashboard')

@section('title', 'Record Attendance')

@section('dashboard')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Record Attendance</h1>
    
    <form method="POST" action="{{ route('attendance.store') }}" class="space-y-6 bg-white p-6 rounded-xl shadow">
        @csrf
        
        <!-- Class and Date Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                <select name="school_class_id" id="class-select" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Choose a class --</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('school_class_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" id="date-input" value="{{ old('date', now()->toDateString()) }}" 
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Students List (Initially Hidden) -->
        <div id="students-section" class="hidden">
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Student Attendance</h3>
                <div id="students-list" class="space-y-3">
                    <!-- Students will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t">
            <div class="text-sm text-gray-500">
                <span id="status-message">Select a class and date to load students</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" id="save-button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Save Attendance
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class-select');
    const dateInput = document.getElementById('date-input');
    const studentsSection = document.getElementById('students-section');
    const studentsList = document.getElementById('students-list');
    const saveButton = document.getElementById('save-button');
    const statusMessage = document.getElementById('status-message');

    function checkFormValidity() {
        const classSelected = classSelect.value !== '';
        const dateSelected = dateInput.value !== '';
        
        if (classSelected && dateSelected) {
            saveButton.disabled = false;
            statusMessage.textContent = 'Ready to save attendance';
        } else {
            saveButton.disabled = true;
            statusMessage.textContent = 'Select a class and date to load students';
        }
    }

    function loadStudents() {
        const classId = classSelect.value;
        const date = dateInput.value;

        if (!classId || !date) {
            studentsSection.classList.add('hidden');
            return;
        }

        statusMessage.textContent = 'Loading students...';
        
        fetch('{{ route("attendance.students") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                school_class_id: classId
            })
        })
        .then(response => response.json())
        .then(students => {
            if (students.length === 0) {
                studentsList.innerHTML = '<p class="text-gray-500 text-center py-4">No students found in this class.</p>';
            } else {
                studentsList.innerHTML = students.map(student => `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-medium">${student.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">${student.name}</h4>
                                <p class="text-sm text-gray-500">${student.email}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center">
                                <input type="radio" name="entries[${student.id}]" value="present" class="mr-2" checked>
                                <span class="text-sm">Present</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="entries[${student.id}]" value="absent" class="mr-2">
                                <span class="text-sm">Absent</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="entries[${student.id}]" value="late" class="mr-2">
                                <span class="text-sm">Late</span>
                            </label>
                        </div>
                    </div>
                `).join('');
            }
            studentsSection.classList.remove('hidden');
            statusMessage.textContent = `Loaded ${students.length} student(s)`;
        })
        .catch(error => {
            console.error('Error loading students:', error);
            statusMessage.textContent = 'Error loading students. Please try again.';
            studentsList.innerHTML = '<p class="text-red-500 text-center py-4">Error loading students. Please try again.</p>';
        });
    }

    // Event listeners
    classSelect.addEventListener('change', function() {
        checkFormValidity();
        if (this.value && dateInput.value) {
            loadStudents();
        } else {
            studentsSection.classList.add('hidden');
        }
    });

    dateInput.addEventListener('change', function() {
        checkFormValidity();
        if (this.value && classSelect.value) {
            loadStudents();
        } else {
            studentsSection.classList.add('hidden');
        }
    });

    // Initial check
    checkFormValidity();
});
</script>
@endsection



