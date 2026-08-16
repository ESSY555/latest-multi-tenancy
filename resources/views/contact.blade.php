@extends('layouts.app')

@section('title', 'Contact Us - School Management System')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 md:py-32 min-h-[420px] md:min-h-[520px] bg-slate-900">
        <img src="{{ asset('images/Home-screen1.png') }}" alt="Students at Bezaleel International School"
            class="absolute inset-0 w-full h-full object-cover object-top" />
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/75 via-indigo-900/65 to-purple-900/75"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Contact Us</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Get in touch with us. We're here to help and answer any questions you may have.
            </p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Contact a Branch Near You</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Choose your nearest branch for faster response and location-specific support.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @forelse($branches as $branchItem)
                    @php
                        $fullAddress = collect([$branchItem->address, $branchItem->city, $branchItem->state, $branchItem->country])
                            ->filter()
                            ->implode(', ');
                        $isSelectedBranch = isset($branch) && $branch->id === $branchItem->id;
                    @endphp
                    <div class="bg-white p-8 rounded-xl shadow-lg border {{ $isSelectedBranch ? 'border-blue-500 ring-2 ring-blue-100' : 'border-transparent' }}">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $branchItem->name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $branchItem->code }}</p>

                            <p class="text-gray-700 font-medium">Phone</p>
                            <p class="text-gray-600 mb-3">{{ $branchItem->phone ?: 'Not provided' }}</p>

                            <p class="text-gray-700 font-medium">Address</p>
                            <p class="text-gray-600 mb-5">{{ $fullAddress ?: 'Not provided' }}</p>

                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('contact.branch', $branchItem->id) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors">
                                    Contact This Branch
                                </a>
                                @if($branchItem->phone)
                                    <a href="tel:{{ preg_replace('/\s+/', '', $branchItem->phone) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                        Call Branch
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-3 bg-white rounded-xl shadow-lg p-8 text-center">
                        <p class="text-gray-600">No branches available yet. Please create branches from the admin dashboard.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Send us a Message</h2>
                <p class="text-lg text-gray-600">
                    Fill out the form below and we'll get back to you as soon as possible.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <form id="contact-form" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter your full name">
                            <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter your email address">
                            <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter your phone number">
                            <div id="phone-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch *</label>
                            <select id="branch_id" name="branch_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Select a branch</option>
                                @foreach($branches as $branchOption)
                                    <option value="{{ $branchOption->id }}" {{ isset($branch) && $branch->id === $branchOption->id ? 'selected' : '' }}>
                                        {{ $branchOption->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="branch_id-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <input type="text" id="subject" name="subject" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="What is this regarding?">
                        <div id="subject-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea id="message" name="message" rows="6" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                  placeholder="Please describe your inquiry or message..."></textarea>
                        <div id="message-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="flex items-center justify-center">
                        <button type="submit" id="submit-btn" 
                                class="px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submit-text">Send Message</span>
                            <span id="submit-loading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Success/Error Messages -->
                <div id="form-message" class="mt-6 hidden">
                    <div id="success-message" class="bg-green-50 border border-green-200 rounded-lg p-4 hidden">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p id="success-text" class="text-sm font-medium text-green-800"></p>
                            </div>
                        </div>
                    </div>

                    <div id="error-message" class="bg-red-50 border border-red-200 rounded-lg p-4 hidden">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p id="error-text" class="text-sm font-medium text-red-800"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Find Us</h2>
                <p class="text-lg text-gray-600">
                    Visit our School premises and experience our learning environment firsthand.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-96">
                    <iframe
                        title="Bezaleel International School Location"
                        src="https://maps.google.com/maps?q=Berger%20Quarry%2C%20Mpape%2C%20Abuja%2C%20Nigeria&z=15&output=embed"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Office Hours Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Office Hours</h2>
                <p class="text-lg text-gray-600">
                    We're available during these hours to assist you.
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Administrative Office</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monday - Friday</span>
                                <span class="font-medium text-gray-900">8:00 AM - 4:00 PM</span>
                            </div>
                            {{-- <div class="flex justify-between">
                                <span class="text-gray-600">Saturday</span>
                                <span class="font-medium text-gray-900">9:00 AM - 2:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sunday</span>
                                <span class="font-medium text-gray-900">Closed</span>
                            </div> --}}
                            </div>
                            </div>

                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Admissions Office</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Monday - Friday</span>
                                        <span class="font-medium text-gray-900">8:00 AM - 4:00 PM</span>
                                        </div>
                                    {{-- <div class="flex justify-between">
                                        <span class="text-gray-600">Saturday</span>
                                        <span class="font-medium text-gray-900">9:00 AM - 3:00 PM</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sunday</span>
                                        <span class="font-medium text-gray-900">Closed</span>
                                    </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    const formMessage = document.getElementById('form-message');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    const successText = document.getElementById('success-text');
    const errorText = document.getElementById('error-text');

    // Clear all error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(element => {
            element.classList.add('hidden');
            element.textContent = '';
        });
    }

    // Show error message for a specific field
    function showFieldError(fieldName, message) {
        const errorElement = document.getElementById(fieldName + '-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    // Show success message
    function showSuccess(message) {
        clearErrors();
        successText.textContent = message;
        successMessage.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        formMessage.classList.remove('hidden');
        
        // Reset form
        form.reset();
        
        // Hide message after 5 seconds
        setTimeout(() => {
            formMessage.classList.add('hidden');
        }, 5000);
    }

    // Show error message
    function showError(message) {
        clearErrors();
        errorText.textContent = message;
        errorMessage.classList.remove('hidden');
        successMessage.classList.add('hidden');
        formMessage.classList.remove('hidden');
        
        // Hide message after 5 seconds
        setTimeout(() => {
            formMessage.classList.add('hidden');
        }, 5000);
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous messages
        clearErrors();
        formMessage.classList.add('hidden');
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        // Get form data
        const formData = new FormData(form);
        
        // Make AJAX request
        fetch('{{ route("contact.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
            } else {
                if (data.errors) {
                    // Show field-specific errors
                    Object.keys(data.errors).forEach(field => {
                        showFieldError(field, data.errors[field][0]);
                    });
                } else {
                    showError(data.message || 'An error occurred. Please try again.');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Network error. Please check your connection and try again.');
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        });
    });
});
</script>
@endpush

