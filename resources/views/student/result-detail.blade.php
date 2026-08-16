@extends('layouts.dashboard')

@section('title', 'Result Detail - ' . ($result->subject->name ?? 'N/A'))

@section('dashboard')
<style>
    @media print {
        body {
            background-color: white;
        }
        .bg-gray-100 {
            background-color: white !important;
        }
        .shadow-md {
            box-shadow: none !important;
        }
        button, a:not(.printable), .no-print {
            display: none !important;
        }
        .bg-blue-50 {
            display: none !important;
        }
        .mb-6 a {
            display: none !important;
        }
    }
</style>

<div class="min-h-screen bg-transparent py-0">
    <div class="w-full px-0">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('student.results') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                ← Back to Results
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $result->subject->name ?? 'N/A' }}</h1>
                    <p class="text-gray-600 mt-1">Student: <strong>{{ $user->name }}</strong></p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Admission No: <strong>{{ $student->admission_number }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Print & Export Buttons -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex gap-3 flex-wrap">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
            
            <a href="{{ route('student.results.subject-export-pdf', $result->subject_id) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>

        <!-- Score Details -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <!-- CAT1 -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 text-sm mb-3 uppercase font-semibold">CAT 1</p>
                <p class="text-4xl font-bold text-blue-600">{{ $result->ca1 }}</p>
                <p class="text-gray-500 text-xs mt-2">Continuous Assessment 1</p>
            </div>

            <!-- NPW (CA2) -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 text-sm mb-3 uppercase font-semibold">NPW</p>
                <p class="text-4xl font-bold text-green-600">{{ $result->ca2 }}</p>
                <p class="text-gray-500 text-xs mt-2">Continuous Assessment 2</p>
            </div>

            <!-- CAT2 (CA3) -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 text-sm mb-3 uppercase font-semibold">CAT 2</p>
                <p class="text-4xl font-bold text-purple-600">{{ $result->ca3 }}</p>
                <p class="text-gray-500 text-xs mt-2">Continuous Assessment 3</p>
            </div>

            <!-- EXAM -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600 text-sm mb-3 uppercase font-semibold">Exam</p>
                <p class="text-4xl font-bold text-orange-600">{{ $result->exam }}</p>
                <p class="text-gray-500 text-xs mt-2">Final Examination</p>
            </div>
        </div>

        <!-- Total and Grade -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Total Score -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-600 text-lg mb-4">Total Score</p>
                <p class="text-5xl font-bold text-indigo-600">{{ $result->total }}</p>
                <p class="text-gray-500 text-sm mt-4">Out of {{ ($result->ca1 + $result->ca2 + $result->ca3 + $result->exam) > 0 ? 'possible ' . ($result->ca1 + $result->ca2 + $result->ca3 + $result->exam) : '400' }}</p>
            </div>

            <!-- Grade -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-600 text-lg mb-4">Grade</p>
                @if($result->grade === 'A')
                    <p class="text-5xl font-bold text-green-600">{{ $result->grade }}</p>
                    <p class="text-gray-500 text-sm mt-4">Excellent (80+)</p>
                @elseif($result->grade === 'B')
                    <p class="text-5xl font-bold text-blue-600">{{ $result->grade }}</p>
                    <p class="text-gray-500 text-sm mt-4">Very Good (70-79)</p>
                @elseif($result->grade === 'C')
                    <p class="text-5xl font-bold text-yellow-600">{{ $result->grade }}</p>
                    <p class="text-gray-500 text-sm mt-4">Good (60-69)</p>
                @elseif($result->grade === 'D')
                    <p class="text-5xl font-bold text-orange-600">{{ $result->grade }}</p>
                    <p class="text-gray-500 text-sm mt-4">Satisfactory (50-59)</p>
                @else
                    <p class="text-5xl font-bold text-red-600">{{ $result->grade }}</p>
                    <p class="text-gray-500 text-sm mt-4">Needs Improvement (<50)</p>
                @endif
            </div>
        </div>

        <!-- Performance Breakdown -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Score Breakdown</h2>
            
            <div class="space-y-4">
                <!-- CAT1 Progress -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700 font-semibold">CAT1 Score</span>
                        <span class="text-blue-600 font-bold">{{ $result->ca1 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($result->ca1 / 100) * 100 }}%"></div>
                    </div>
                </div>

                <!-- NPW Progress -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700 font-semibold">NPW Score</span>
                        <span class="text-green-600 font-bold">{{ $result->ca2 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($result->ca2 / 100) * 100 }}%"></div>
                    </div>
                </div>

                <!-- CAT2 Progress -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700 font-semibold">CAT2 Score</span>
                        <span class="text-purple-600 font-bold">{{ $result->ca3 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($result->ca3 / 100) * 100 }}%"></div>
                    </div>
                </div>

                <!-- EXAM Progress -->
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700 font-semibold">Exam Score</span>
                        <span class="text-orange-600 font-bold">{{ $result->exam }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: {{ ($result->exam / 100) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Scale Reference -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Grade Scale Reference</h3>
            <div class="grid grid-cols-5 gap-4">
                <div class="text-center">
                    <p class="font-bold text-green-600 text-2xl">A</p>
                    <p class="text-sm text-gray-600">80 - 100</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-blue-600 text-2xl">B</p>
                    <p class="text-sm text-gray-600">70 - 79</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-yellow-600 text-2xl">C</p>
                    <p class="text-sm text-gray-600">60 - 69</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-orange-600 text-2xl">D</p>
                    <p class="text-sm text-gray-600">50 - 59</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-red-600 text-2xl">F</p>
                    <p class="text-sm text-gray-600">Below 50</p>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

