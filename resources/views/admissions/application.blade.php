@extends('layouts.app')

@section('title', 'Application Form - Bezaleel International School')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="w-20 h-20 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Application Form</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Start your child's educational journey with us by completing this application form
            </p>
        </div>
    </section>

    <!-- Application Form -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Student Application</h2>
                    <p class="text-lg text-gray-600">Please fill out all required fields marked with *</p>
                </div>

                <form id="admissionForm" class="space-y-6" action="{{ route('admissions.store') }}" method="POST">
                    @csrf

                    <!-- Branch Selection -->
                    <div class="bg-gray-50 p-6 rounded-xl form-section">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            School Branch Selection
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select School Branch *</label>
                                <select name="branchId" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                                    <option value="">Choose a branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }} - {{ $branch->address }}</option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Please select the branch where you want to enroll your
                                    child</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="bg-gray-50 p-6 rounded-xl form-section">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Student Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="firstName" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="lastName" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                <input type="text" name="middleName"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                                <input type="date" name="dateOfBirth" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                <select name="gender" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                                <input type="text" name="nationality"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State of Origin</label>
                                <input type="text" name="stateOfOrigin"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Local Government Area</label>
                                <input type="text" name="localGovernmentArea"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                                <input type="text" name="religion"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Church Denomination</label>
                                <input type="text" name="churchDenomination"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Language of Communication</label>
                                <input type="text" name="languageOfCommunication"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. of Children in Family</label>
                                <input type="number" min="1" name="numberOfChildrenInFamily"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position in Family</label>
                                <input type="number" min="1" name="positionInFamily"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">School Last Attended</label>
                                <input type="text" name="schoolLastAttended"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Class Last Attended</label>
                                <input type="text" name="classLastAttended"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Grade Level (if
                                    applicable)</label>
                                <select name="currentGrade"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                                    <option value="">Select Grade</option>
                                    <option value="pre-k">Pre-Kindergarten</option>
                                    <option value="k">Kindergarten</option>
                                    <option value="1">Grade 1</option>
                                    <option value="2">Grade 2</option>
                                    <option value="3">Grade 3</option>
                                    <option value="4">Grade 4</option>
                                    <option value="5">Grade 5</option>
                                    <option value="6">Grade 6</option>
                                    <option value="7">Jss1</option>
                                    <option value="8">Jss2</option>
                                    <option value="9">Jss3</option>
                                    <option value="10">Sss1</option>
                                    <option value="11">Sss2</option>
                                    <option value="12">Sss3</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Any Health Challenges?</label>
                                <div class="flex flex-wrap items-center gap-6 pt-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="hasHealthChallenges" value="1"
                                            class="mr-2 text-blue-600 focus:ring-blue-500">
                                        Yes
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="hasHealthChallenges" value="0" checked
                                            class="mr-2 text-blue-600 focus:ring-blue-500">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="md:col-span-2 hidden" id="healthChallengesDetailsWrap">
                                <label class="block text-sm font-medium text-gray-700 mb-2">If yes, please state details</label>
                                <textarea name="healthChallengesDetails" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">What should be done if a crisis arises?</label>
                                <select name="crisisResponse"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                                    <option value="">Select Response</option>
                                    <option value="administer_first_aid">Administer first aid</option>
                                    <option value="do_not_administer_first_aid">Do not administer first aid</option>
                                    <option value="call_parents_guardians">Call parents/guardians</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Parent/Guardian Information -->
                    <div class="bg-gray-50 p-6 rounded-xl form-section">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Parent/Guardian Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Contact Name *</label>
                                <input type="text" name="primaryContactName" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Relationship to Student
                                    *</label>
                                <select name="relationship" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                                    <option value="">Select Relationship</option>
                                    <option value="parent">Parent</option>
                                    <option value="guardian">Guardian</option>
                                    <option value="grandparent">Grandparent</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="phoneNumber" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                <textarea name="address" required rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input"
                                    placeholder="Enter your complete address"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
                                <input type="text" name="fatherName"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Name</label>
                                <input type="text" name="motherName"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Residential Address</label>
                                <input type="text" name="fatherResidentialAddress"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Residential Address</label>
                                <input type="text" name="motherResidentialAddress"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Occupation</label>
                                <input type="text" name="fatherOccupation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Occupation</label>
                                <input type="text" name="motherOccupation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Office Address</label>
                                <input type="text" name="fatherOfficeAddress"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Office Address</label>
                                <input type="text" name="motherOfficeAddress"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Phone Number</label>
                                <input type="tel" name="fatherPhoneNumber"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Phone Number</label>
                                <input type="tel" name="motherPhoneNumber"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 p-6 rounded-xl form-section">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Additional Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">How did you hear about our
                                    school?</label>
                                <select name="hearAboutSchool"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input">
                                    <option value="">Select Option</option>
                                    <option value="website">Website</option>
                                    <option value="social-media">Social Media</option>
                                    <option value="referral">Referral</option>
                                    <option value="advertisement">Advertisement</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Any special considerations or
                                    additional information?</label>
                                <textarea name="additionalInfo" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors form-input"
                                    placeholder="Please share any relevant information about your child's needs, interests, or circumstances..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit"
                            class="px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Need Help with Your Application?</h2>
            <p class="text-lg text-gray-600 mb-8">Our admissions team is here to help you every step of the way</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Call Us</h3>
                    <p class="text-gray-600">+1 (555) 123-4567</p>
                    <p class="text-sm text-gray-500">Mon-Fri, 8:00 AM - 4:00 PM</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Us</h3>
                    <p class="text-gray-600">admissions@Bezaleel.edu</p>
                    <p class="text-sm text-gray-500">We'll respond within 24 hours</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Visit Us</h3>
                    <p class="text-gray-600">123 Education Street</p>
                    <p class="text-sm text-gray-500">Schedule a campus tour</p>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .form-input:focus {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
            }

            .form-section {
                transition: all 0.3s ease;
            }

            .form-section:hover {
                transform: translateY(-2px);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('admissionForm');
                const healthRadios = document.querySelectorAll('input[name="hasHealthChallenges"]');
                const healthDetailsWrap = document.getElementById('healthChallengesDetailsWrap');

                function toggleHealthDetails() {
                    const selected = document.querySelector('input[name="hasHealthChallenges"]:checked');
                    const showDetails = selected && selected.value === '1';
                    if (healthDetailsWrap) {
                        healthDetailsWrap.classList.toggle('hidden', !showDetails);
                    }
                }

                healthRadios.forEach((radio) => {
                    radio.addEventListener('change', toggleHealthDetails);
                });
                toggleHealthDetails();

                if (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        // Get form data
                        const formData = new FormData(this);
                        const data = Object.fromEntries(formData);

                        // Basic validation
                        if (!data.branchId || !data.firstName || !data.lastName || !data.dateOfBirth || !data.gender ||
                            !data.primaryContactName || !data.relationship || !data.phoneNumber || !data.email || !data.address) {
                            alert('Please fill in all required fields marked with *');
                            return;
                        }

                        // Submit form via AJAX
                        fetch('{{ route("admissions.store") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                            .then(async response => {
                                const data = await response.json();
                                if (!response.ok) {
                                    throw data;
                                }
                                return data;
                            })
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    form.reset();
                                    toggleHealthDetails();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (error.errors) {
                                    const firstError = Object.values(error.errors)[0];
                                    alert(Array.isArray(firstError) ? firstError[0] : 'Please review the form and try again.');
                                    return;
                                }
                                alert(error.message || 'There was an error submitting your application. Please try again or contact us directly.');
                            });
                    });
                }
            });
        </script>
    @endpush
@endsection
