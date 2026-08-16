@extends('layouts.dashboard')

@section('title', 'Create Academic Term')

@section('dashboard')
    <div class="container mx-auto px-6 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('academic-calendar.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    ← Back to Academic Calendar
                </a>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Create Academic Term</h1>
                <p class="text-gray-600 mt-2">Add a new term to <strong>{{ $academicYear->name }}</strong></p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('academic-calendar.terms.store', $academicYear->id) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Term Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Term Name *</label>
                            <select name="name" id="name" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select a term</option>
                                <option value="First Term" {{ old('name') == 'First Term' ? 'selected' : '' }}>First Term</option>
                                <option value="Second Term" {{ old('name') == 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                <option value="Third Term" {{ old('name') == 'Third Term' ? 'selected' : '' }}>Third Term</option>
                            </select>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date *</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                                   min="{{ $academicYear->start_date->format('Y-m-d') }}"
                                   max="{{ $academicYear->end_date->format('Y-m-d') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Must be between {{ $academicYear->start_date->format('M d, Y') }} and {{ $academicYear->end_date->format('M d, Y') }}</p>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date *</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                                   min="{{ $academicYear->start_date->format('Y-m-d') }}"
                                   max="{{ $academicYear->end_date->format('Y-m-d') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Must be after the start date</p>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Brief description of the term">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Term Type Flags -->
                        <div class="space-y-3 border-t pt-6">
                            <h3 class="text-sm font-medium text-gray-900">Term Type</h3>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_exam_term" id="is_exam_term" value="1" 
                                       {{ old('is_exam_term') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_exam_term" class="ml-2 block text-sm text-gray-900">
                                    Is Exam Term
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 ml-6">Check if this term has examinations</p>

                            <div class="flex items-center mt-4">
                                <input type="checkbox" name="is_break_term" id="is_break_term" value="1" 
                                       {{ old('is_break_term') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_break_term" class="ml-2 block text-sm text-gray-900">
                                    Is Break Term
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 ml-6">Check if this is a holiday or break period</p>
                        </div>

                        <!-- Principal Signature Section -->
                        <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-700 uppercase mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Principal's Signature (applies to all results in this term)
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-2 flex flex-col items-center">
                                    <canvas id="principal-signature-pad" class="w-full h-40 cursor-crosshair bg-white touch-none"
                                        style="max-width: 500px;"></canvas>
                                    <div class="w-full border-t border-gray-200 mt-2 pt-2 flex justify-between items-center px-4">
                                        <span class="text-xs text-gray-400 font-medium italic">Draw the principal's signature above</span>
                                        <button type="button" onclick="clearPrincipalSignature()"
                                            class="text-xs font-bold text-red-500 hover:text-red-700 uppercase transition-colors px-2 py-1 rounded hover:bg-red-50">
                                            Clear Signature
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="principal_signature" id="principal_signature_input">
                                @error('principal_signature')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6">
                            <a href="{{ route('academic-calendar.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Term
                            </button>
                        </div>
                        </div>
                        </form>
                        </div>
                        </div>
                        </div>

                        document.addEventListener('DOMContentLoaded', function() {
                        const startDateInput = document.getElementById('start_date');
                        const endDateInput = document.getElementById('end_date');

                        // When start date changes, ensure end date is after it
                        startDateInput.addEventListener('change', function() {
                        if (endDateInput.value && startDateInput.value > endDateInput.value) {
                        endDateInput.value = startDateInput.value;
                        }
                        });
                        });
                        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const startDateInput = document.getElementById('start_date');
                                const endDateInput = document.getElementById('end_date');
                                // When start date changes, ensure end date is after it
                                startDateInput.addEventListener('change', function () {
                                    if (endDateInput.value && startDateInput.value > endDateInput.value) {
                                        endDateInput.value = startDateInput.value;
                                    }
                                });

                                // Principal SignaturePad
                                const principalCanvas = document.getElementById('principal-signature-pad');
                                let principalSignaturePad;
                                if (principalCanvas) {
                                    principalSignaturePad = new SignaturePad(principalCanvas, {
                                        backgroundColor: 'rgb(255,255,255)'
                                    });

                                    function resizePrincipalCanvas() {
                                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                        principalCanvas.width = principalCanvas.offsetWidth * ratio;
                                        principalCanvas.height = principalCanvas.offsetHeight * ratio;
                                        principalCanvas.getContext('2d').scale(ratio, ratio);
                                        principalSignaturePad.clear();
                                    }
                                    window.addEventListener('resize', resizePrincipalCanvas);
                                    resizePrincipalCanvas();
                                    window.clearPrincipalSignature = () => principalSignaturePad.clear();
                                }

                                // Handle form submission
                                const form = document.querySelector('form');
                                if (form && principalSignaturePad) {
                                    form.addEventListener('submit', function (e) {
                                        if (principalSignaturePad.isEmpty()) {
                                            e.preventDefault();
                                            alert('Please provide the principal\'s signature before submitting.');
                                            return;
                                        }
                                        document.getElementById('principal_signature_input').value = principalSignaturePad.toDataURL();
                                    });
                                }
    });
    </script>
@endsection

