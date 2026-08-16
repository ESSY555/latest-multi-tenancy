@extends('layouts.dashboard')

@section('title', 'My Academic Results')

@section('dashboard')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-[1100px] mx-auto mb-6 px-4 no-print">
            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <i class="fas fa-filter text-blue-600"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Browse Prevous Results</h2>
                </div>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="academic_year_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Academic Year</label>
                        <select name="academic_year_id" id="academic_year_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 transition-all font-medium"
                            onchange="this.form.submit();">
                            <option value="">-- Select Academic Year --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedAcademicYear && $selectedAcademicYear->id == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="term_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Term</label>
                        <select name="term_id" id="term_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 transition-all font-medium"
                            onchange="this.form.submit();">
                            <option value="">-- Select Term --</option>
                            @foreach($terms as $t)
                                <option value="{{ $t->id }}" {{ $selectedTermId == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} - {{ $t->academicYear->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        @if($results->isEmpty())
            <div class="max-w-[1100px] mx-auto px-4">
                @if($hasPendingResults)
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-8 text-center shadow-sm">
                        <div class="mx-auto h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clock text-orange-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Results Pending Approval</h3>
                        <p class="text-gray-600 max-w-md mx-auto">Your results for this term have been entered but are currently awaiting administrator approval. Please check back later or contact the admin office.</p>
                    </div>
                @else
                    <div class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-sm">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Results Available</h3>
                        <p class="text-gray-500 max-w-md mx-auto">We couldn't find any approved results for the selected academic year and term. If you believe this is an error, please contact your form teacher.</p>
                    </div>
                @endif
            </div>
        @else
                            @php
    $attendancePresent = $results->first()?->attendance_present;
    $attendanceOpened = ($results->first()?->attendance_present ?? 0)
        + ($results->first()?->attendance_absent ?? 0)
        + ($results->first()?->attendance_late ?? 0);
                            @endphp

                            <div class="max-w-[1100px] mx-auto border border-gray-800 p-5 bg-white shadow-sm relative overflow-hidden">
                                <div class="flex justify-between mb-4 pb-4 border-b border-gray-100">
                                    <div class="w-[80px] h-[80px] shrink-0 self-start mt-2">
                                        <img src="{{ asset('images/bezalee-logo-main.PNG') }}" alt="logo" class="w-20 h-20 object-contain">
                                    </div>

                                    <div class="text-center flex-1 px-4">
                                        <h1 class="text-4xl font-bold uppercase tracking-tight text-blue-600 font-bold">BEZALEEL INTERNATIONAL
                                            SCHOOL</h1>
                                        <h2 class="text-3xl font-semibold uppercase text-blue-600 font-bold">MPAPE ABUJA</h2>
                                        <p class="text-sm italic font-medium text-red-600 font-bold">Motto: Towards Excellence</p>
                                        <div class="mt-2">
                                            <p class="font-bold text-blue-600 font-bold">TELEPHONE:<span class="font-bold">07014907969,</span>
                                                <span class=" font-bold">08052123760</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="w-[80px] h-[80px] shrink-0 self-start mt-2">
                                        @php
                                            $rawPhoto = $student->profile_photo ?? $student->studentProfile?->profile_photo;
                                        @endphp
                                        @if ($rawPhoto)
                                            <img src="{{ str_starts_with($rawPhoto, 'http') ? $rawPhoto : asset('uploads/profile-photos/' . basename($rawPhoto)) }}" alt="passport"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="text-[8px] text-gray-400 font-bold text-center p-1 uppercase leading-tight italic">
                                                Student<br>Passport</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 my-5 text-[13px]">
                                    <div class="space-y-3">
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">SESSION:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-bold">{{ $term->academicYear->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">STUDENT NAME:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-semibold text-base font-bold">{{ $student->name }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">CLASS:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-bold">{{ $results->first()?->schoolClass->name ?? $student->enrollments->first()?->schoolClass->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">ADMISSION NO:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-bold">{{ $student->studentProfile->admission_number ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold uppercase">Term:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-medium">{{ $term->name }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold uppercase">Next Term
                                                Begins:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase">{{ $results->first()?->next_term_begins ?? '—' }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">TOTAL AVERAGE SCORE:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-bold text-lg">{{ number_format($averageScore, 2) }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">NO OF TIMES SCHOOL OPENED:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-bold">{{ $attendanceOpened > 0 ? $attendanceOpened : '—' }}</span>
                                        </div>
                                        <div class="flex items-end"><span class="min-w-[140px] font-bold">NO OF ATTENDANCE:</span><span
                                                class="flex-1 border-b border-gray-600 pb-0.5 uppercase font-bold">{{ $attendancePresent !== null ? $attendancePresent : '—' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-span-1 md:col-span-2 flex mt-3 items-end">
                                        <span class="min-w-[140px] font-bold">NEXT TERM FEES:</span>
                                        <span
                                            class="flex-1 border-b border-gray-600 h-6 uppercase font-bold">{{ $results->first()?->next_term_fees ?? '—' }}</span>
                                        <p class="text-[12px] text-red-600 ml-2 font-bold">(To be paid during holiday /Resumption day)</p>
                                    </div>
                                </div>

                                <div class="relative mt-4 overflow-hidden">
                                    <x-result-watermark />
                                    <table class="relative z-10 w-full border-collapse border border-gray-800 text-[11px]">
                                        <thead class="bg-gray-100 italic uppercase text-blue-600 font-bold">
                                            <tr>
                                                <th class="border border-gray-800 p-1 w-[40px]">S/N</th>
                                                <th class="border border-gray-800 p-1 text-left pl-2">SUBJECTS</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">CA1 (10)</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">CA2 (10)</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">CA3 (10)</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">EXAM (70)</th>
                                                <th class="border border-gray-800 p-1 w-[70px]">TOTAL (100)</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">CLASS AVG</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">HIGHEST IN CLASS</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">LOWEST IN CLASS</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">GRADE</th>
                                                <th class="border border-gray-800 p-1 w-[60px]">POSITION</th>
                                                <th class="border border-gray-800 p-1">REMARK</th>
                                                <!-- <th class="border border-gray-800 p-1.5 w-[80px]">SIGNATURE</th> -->
                                                </tr>
                                                </thead>
                                                <tbody class="text-center font-medium">
                                                    @foreach($results as $index => $result)
                                                            <tr class="hover:bg-gray-50 uppercase text-blue-600 font-bold">
                                                                <td class="border border-gray-800 p-1.5 text-[#0826d8]">{{ $index + 1 }}</td>
                                                                <td class="border border-gray-800 p-1.5 text-left pl-2 font-bold text-[#0826d8]">
                                                                    {{ $result->subject->name }}
                                                                </td>
                                                                <td class="border border-gray-800 p-1.5">{{ number_format($result->ca1, 0) }}</td>
                                                                <td class="border border-gray-800 p-1.5">{{ number_format($result->ca2, 0) }}</td>
                                                                <td class="border border-gray-800 p-1.5">{{ number_format($result->ca3, 0) }}</td>
                                                                <td class="border border-gray-800 p-1.5 font-medium">{{ number_format($result->exam, 0) }}</td>
                                                                <td class="border border-gray-800 p-1.5 font-bold text-[13px] {{ $result->total < 45 ? 'text-red-600' : '' }}">
                                                                    {{ number_format($result->total, 0) }}
                                                                </td>
                                                                <td class="border border-gray-800 p-1.5 font-bold text-[13px]">
                                                                    {{ number_format((float) ($result->class_average ?? 0), 2) }}</td>
                                                                <td class="border border-gray-800 p-1.5 font-bold text-[13px]">
                                                                    {{ number_format($result->class_highest ?? 0, 0) }}</td>
                                                                <td class="border border-gray-800 p-1.5 font-bold text-[13px]">
                                                                    {{ number_format($result->class_lowest ?? 0, 0) }}</td>
                                                                <td class="border border-gray-800 p-1.5 font-bold text-[#0826d8]">{{ $result->grade ?? '-' }}
                                                                </td>
                                                                <td class="border border-gray-800 p-1.5 italic text-[#0826d8]">
                                                                    {{ $result->position ? $result->position . ((($result->position % 100) >= 11 && ($result->position % 100) <= 13) ? 'th' : (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$result->position % 10])) : '-' }}
                                                                </td>
                                                                <td class="border border-gray-800 p-1.5 text-[10px] leading-tight">{{ $result->remark ?? '-' }}
                                                                </td>
                                                                <!-- <td class="border border-gray-800 p-1 h-10">
                                                                                                @if($result->form_teacher_signature)
                                                                                                    <img src="{{ asset('storage/' . $result->form_teacher_signature) }}" alt="signature"
                                                                                                        style="max-width: 70px; max-height: 40px;" class="mx-auto object-contain">
                                                                                                @endif
                                                                                            </td> -->
                                                        </tr>
                                                    @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-6 items-start justify-center">
                                    <div class="w-full">
                                        <table class="w-full border border-black table-fixed text-[11px] text-blue-600 font-bold">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"
                                                        class="border border-black bg-gray-100 p-1 font-bold italic uppercase text-[#0826d8]">
                                                        Psychomotor Domain</th>
                                                </tr>
                                            </thead>
                                            <tbody class="uppercase text-center">
                                                @php
    $psyTraits = ['Handwriting', 'Fluency', 'Participation in Sports', 'Handling Tools (Practical)', 'Music/Performance Skill'];
    $psychomotorData = $results->first()->psychomotor ?? [];
                                                @endphp
                                                @foreach($psyTraits as $trait)
                                                    <tr class="h-10 border-b border-black">
                                                        <td class="w-2/3 align-middle text-left pl-2 uppercase font-semibold text-[#0826d8]">
                                                            {{ $trait }}
                                                        </td>
                                                        <td class="w-1/3 border-l border-black p-0 text-center uppercase text-[12px] font-bold">
                                                            {{ $psychomotorData[$trait] ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="w-full">
                                        <table class="w-full border border-black table-fixed text-[11px] text-blue-600 font-bold">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"
                                                        class="border border-black bg-gray-100 p-1 font-bold italic uppercase text-[#0826d8]">
                                                        Affective Domain</th>
                                                </tr>
                                            </thead>
                                            <tbody class="uppercase text-center">
                                                @php
    $affTraits = ['General Conduct', 'Spiritual Development', 'Neatness/Cleanliness', 'Punctuality', 'Reliability'];
    $affectiveData = $results->first()->affective ?? [];
                                                @endphp
                                                @foreach($affTraits as $trait)
                                                    <tr class="h-10 border-b border-black">
                                                        <td class="w-2/3 align-middle text-left pl-2 font-semibold text-[#0826d8]">{{ $trait }}</td>
                                                        <td class="w-1/3 border-l border-black p-0 text-center uppercase text-[12px] font-bold">
                                                            {{ $affectiveData[$trait] ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="w-full">
                                        <table
                                            class="w-full border border-black border-collapse table-fixed text-[11px] text-blue-600 font-bold">
                                            <thead>
                                                <tr>
                                                    <th colspan="2"
                                                        class="border border-black bg-gray-100 p-1 font-bold italic uppercase text-[#0826d8]">
                                                        Result Summary</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td class="border border-black p-1.5 font-bold uppercase w-1/2 text-[#0826d8]">Total mark
                                                    obtainable</td>
                                                <td class="border border-black p-1.5 text-center font-bold text-[13px] text-black">
                                                    {{ number_format(($totalSubjects ?? 0) * 100, 0) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border border-black p-1.5 font-bold uppercase w-1/2 text-[#0826d8]">Total mark
                                                    obtained</td>
                                                <td class="border border-black p-1.5 text-center font-bold text-[13px] text-black">
                                                    {{ number_format($totalScore, 0) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border border-black p-1.5 font-bold uppercase text-[#0826d8]">Average</td>
                                                <td class="border border-black p-1.5 text-center font-bold text-black">
                                                    {{ number_format($averageScore, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"
                                                    class="border border-black text-center font-bold bg-gray-50 py-1 uppercase italic tracking-wide text-[#0826d8]">
                                                    Grades Interpretation
                                                </td>
                                            </tr>
                                            @if(($classType ?? 'default') === 'ss')
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">80 – 100 (EXCELLENT)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">A1</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">75 – 79 (VERY GOOD)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">B2</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">70 – 74 (GOOD)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">B3</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">65 – 69 (CREDIT)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">C4</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">60 – 64 (CREDIT)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">C5</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">55 – 59 (CREDIT)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">C6</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">50 – 54 (PASS)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">D7</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">45 – 49 (PASS)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">E8</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">0 – 44 (FAIL)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-red-600">F9</td>
                                                </tr>
                                            @elseif(($classType ?? 'default') === 'js')
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">80 – 100 (EXCELLENT)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">A1</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">55 – 79 (CREDIT)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">C</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">45 – 54 (PASS)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">P</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">0 – 44 (FAIL)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-red-600">F</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">100 - 70 (EXCELLENT)
                                                    </td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">A</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">69 - 60 (VERY GOOD)
                                                    </td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">B</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">59 - 50 (GOOD)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">C</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">49 - 40 (PASS)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-[#0826d8]">D</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-black p-1 font-medium text-[10px] text-[#0826d8]">39 - 0 (FAIL)</td>
                                                    <td class="border border-black p-1 text-center font-bold text-sm text-red-600">F</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row gap-8 mt-4 items-end items-center justify-between text-blue-600 font-bold">
                                    <div class="space-y-8 w-full">
                                        <div class="flex-1 mt-5">
                                            <div class="flex items-center">
                                                <span class="min-w-[170px] font-bold uppercase text-[#0826d8] italic">Form Teacher:</span>
                                                <span class="flex-1 border-b border-gray-700 px-2 italic font-medium uppercase min-h-[24px]">
                                                    {{ $results->first()?->form_teacher_comment ?? 'Comment pending...' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <span class="min-w-[170px] font-bold uppercase text-[#0826d8] italic">Principal's Remark:</span>
                                            <span class="flex-1 border-b border-gray-700 px-2 italic font-medium uppercase min-h-[24px]">
                                                {{ $results->first()?->school_head_comment ?? 'Remark pending...' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-4">
                                        <div class="text-center group">
                                            <p class="text-[10px] font-bold uppercase mb-1 text-[#0826d8]">Form teacher signature</p>
                                            <div
                                                class="border border-black h-12 w-full bg-gray-50 flex items-center justify-center overflow-hidden">
                                                @if($results->first() && $results->first()->form_teacher_signature)
                                                    <img src="{{ asset('storage/' . $results->first()->form_teacher_signature) }}"
                                                        alt="FT Signature" class="h-full object-contain">
                                                @else
                                                    <span
                                                        class="text-gray-400 text-[10px] uppercase font-bold italic tracking-tighter overflow-hidden">SCHOOL
                                                        OFFICIAL SIGN</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center group">
                                            <p class="text-[10px] font-bold uppercase mb-1 text-[#0826d8]">Principal signature</p>
                                            <div
                                                class="border border-black h-12 w-full bg-gray-50 flex items-center justify-center overflow-hidden">
                                                @if($term && $term->principal_signature)
                                                    <img src="{{ asset('storage/' . $term->principal_signature) }}" alt="SH Signature"
                                                        class="h-full object-contain">
                                                @else
                                                    <span
                                                        class="text-gray-400 text-[10px] uppercase font-bold italic tracking-tighter overflow-hidden">SCHOOL
                                                        OFFICIAL SIGN</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="mt-8 border-t border-gray-300 pt-2 flex justify-between items-center text-[10px] text-gray-500 italic">
                                    <span>Result generated via Bezaleel School Portal</span>
                                    <span>Date: {{ now()->format('d M Y (H:i)') }}</span>
                                </div>
                            </div>

                            <!-- Print & Export Controls -->
                            <div class="max-w-[1100px] mx-auto mt-8 mb-12 text-center no-print">
                                <div class="flex flex-wrap justify-center gap-4">
                                    <a href="{{ route('student.results.print', array_merge(request()->all(), ['academic_year_id' => $selectedAcademicYear?->id, 'term_id' => $selectedTermId])) }}"
                                        target="_blank" rel="noopener"
                                        class="px-8 py-3 bg-gray-900 text-white font-black rounded-full hover:bg-black transition-all shadow-xl flex items-center space-x-2 uppercase tracking-widest text-xs">
                                        <i class="fas fa-print text-sm"></i>
                                        <span>Print Report Card</span>
                                    </a>

                                    <a href="{{ route('student.results.export-pdf', array_merge(request()->all(), ['academic_year_id' => $selectedAcademicYear?->id, 'term_id' => $selectedTermId])) }}"
                                        class="px-8 py-3 bg-red-600 text-white font-black rounded-full hover:bg-red-700 transition-all shadow-xl flex items-center space-x-2 uppercase tracking-widest text-xs">
                                        <i class="fas fa-file-pdf text-sm"></i>
                                        <span>Download PDF</span>
                                    </a>

                                    <a href="{{ route('student.results.export-excel', array_merge(request()->all(), ['academic_year_id' => $selectedAcademicYear?->id, 'term_id' => $selectedTermId])) }}"
                                        class="px-8 py-3 bg-green-600 text-white font-black rounded-full hover:bg-green-700 transition-all shadow-xl flex items-center space-x-2 uppercase tracking-widest text-xs">
                                        <i class="fas fa-file-excel text-sm"></i>
                                        <span>Export Excel</span>
                                    </a>
                                </div>
                                <p class="text-gray-400 text-[10px] mt-4 uppercase tracking-[0.2em] font-bold">Official Document • Bezaleel
                                    International School</p>
                            </div>
        @endif
    </div>

    <style>
        @media print {
            body {
                background: white !important;
                margin: 0;
                padding: 0;
                color: #000 !important;
                font-size: 10px !important;
                line-height: 1.2 !important;
            }

            .no-print,
            #sidebar,
            header,
            .dashboard-sidebar,
            .dashboard-header,
            .dashboard-content,
            .fixed,
            .shadow-sm,
            .shadow-lg,
            .shadow-xl {
                display: none !important;
            }

            .min-h-screen,
            .max-w-[1100px],
            .bg-gray-50,
            .bg-white,
            .bg-blue-50,
            .bg-blue-100,
            .bg-indigo-50,
            .bg-purple-50,
            .bg-green-50,
            .bg-orange-50,
            .bg-gray-100 {
                background: white !important;
                box-shadow: none !important;
                border: none !important;
            }

            .min-h-screen {
                min-height: auto !important;
                padding: 0 !important;
            }

            .max-w-[1100px] {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
            }

            * {
                page-break-inside: avoid !important;
            }

            table {
                page-break-inside: auto !important;
                page-break-after: auto !important;
                border-collapse: collapse !important;
            }

            tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            td,
            th {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
                padding: 0.25rem !important;
            }

            @page {
                margin: 0.4cm;
                size: portrait;
            }
        }
    </style>
@endsection
