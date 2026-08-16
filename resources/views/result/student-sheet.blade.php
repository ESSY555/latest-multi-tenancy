                                @extends('layouts.dashboard')

@section('title', 'Student Result Sheet')

@section('dashboard')
                @php
    $isAdminActor = auth()->user()->hasRole('admin') || auth()->user()->is_super_admin;
    $isFormTeacherOnly = auth()->user()->hasRole('form_teacher') && !$isAdminActor;
    $attendancePresent = $results->first()?->attendance_present;
    $attendanceOpened = ($results->first()?->attendance_present ?? 0)
        + ($results->first()?->attendance_absent ?? 0)
        + ($results->first()?->attendance_late ?? 0);
                @endphp
                <div class="min-h-screen bg-white py-4 sm:py-6">
                    <div class="max-w-[1100px] mx-auto border border-gray-800 p-3 sm:p-5 bg-white shadow-sm relative overflow-hidden">
                        {{-- Header Section --}}
                        <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between gap-3 mb-4 pb-4 border-b border-gray-100">
                            {{-- School Logo --}}
                            <div class="w-[80px] h-[80px] shrink-0 self-start mt-2">
                                <img src="{{ asset('images/bezalee-logo-main.PNG') }}" alt="logo" class="w-20 h-20 object-contain">
                            </div>

                            {{-- Center Text Section --}}
                            <div class="text-center flex-1 px-1 sm:px-4">
                                <h1 class="text-xl sm:text-3xl md:text-4xl font-bold uppercase tracking-tight text-blue-600 font-bold">BEZALEEL INTERNATIONAL
                                    SCHOOL</h1>
                                <h2 class="text-lg sm:text-2xl md:text-3xl font-semibold uppercase text-blue-600 font-bold">MPAPE ABUJA</h2>
                                <p class="text-sm italic font-medium text-red-600 font-bold">Motto: Towards Excellence</p>
                                <div class="mt-2">
                                    <p class="font-bold text-blue-600 font-bold">TELEPHONE:<span class="font-bold">07014907969,</span>
                                        <span class=" font-bold">08052123760</span>
                                    </p>
                                    <!-- <h3 class="text-2xl font-bold border-y-2 border-black py-1 inline-block px-6 uppercase">
                                                                                        Termly Academic Report
                                                                                    </h3> -->
                                </div>
                            </div>

                            {{-- Student Passport --}}
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

                        {{-- Student Information --}}
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
                            <!-- Full width row -->
                            <div class="col-span-1 md:col-span-2 flex flex-col sm:flex-row mt-3 sm:items-end gap-1 sm:gap-0">
                                <span class="min-w-[140px] font-bold">NEXT TERM FEES:</span>
                                <span
                                    class="flex-1 border-b border-gray-600 h-6 uppercase font-bold">{{ $results->first()?->next_term_fees ?? '—' }}</span>
                                <p class="text-[12px] text-red-600 ml-2 font-bold">(To be paid during holiday /Resumption day)</p>
                            </div>
                        </div>

                        {{-- Academic Assessment Table --}}
                        <div class="relative mt-4 overflow-x-auto">
                            <x-result-watermark />
                            <table class="relative z-10 w-full min-w-[980px] border-collapse border border-gray-800 text-[11px]">
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
                                        @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                                            <th class="border border-gray-800 p-1 w-[120px] no-print">ACTIONS</th>
                                        @endif
                                        </tr>
                                        </thead>
                                        <tbody class="text-center font-medium">
                                            @forelse($results as $index => $result)
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
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                                                        <td class="border border-gray-800 p-1 no-print">
                                                            <div class="flex items-center justify-center gap-1">
                                                                <a href="{{ route('result.edit', $result) }}"
                                                                    class="px-2 py-1 bg-blue-600 text-white rounded text-[10px] font-bold hover:bg-blue-700"
                                                                    title="Edit {{ $result->subject->name }}">
                                                                    Edit
                                                                </a>
                                                                <form method="POST" action="{{ route('result.destroy', $result) }}"
                                                                    onsubmit="return confirm('Delete {{ $result->subject->name }} result? This cannot be undone.')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="px-2 py-1 bg-red-600 text-white rounded text-[10px] font-bold hover:bg-red-700"
                                                                        title="Delete {{ $result->subject->name }}">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                    <tr>
                                                        <td colspan="15" class="border border-gray-800 p-8 text-center bg-gray-50">
                                                        <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">No subject results
                                                            recorded yet for this term.</span>
                                                        @if(auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('teacher') || auth()->user()->hasRole('admin'))
                                                            <div class="mt-3">
                                                                <a href="{{ route('teacher.score-sheet') }}"
                                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-[10px] font-black rounded-lg hover:bg-blue-700 transition-all uppercase tracking-widest">
                                                                    <i class="fas fa-plus-circle mr-2"></i>Enter Student Scores
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Flexible Grid Sections (Affective & Psychomotor) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-6 items-start justify-center">

                            {{-- Psychomotor Skills --}}
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
                                                <td class="w-1/3 border-l border-black p-0">
                                                    <input type="text" form="studentSheetCommentForm" name="psychomotor[{{ $trait }}]"
                                                        value="{{ $psychomotorData[$trait] ?? '' }}"
                                                        class="w-full h-full border-none focus:ring-0 text-center uppercase text-[12px] bg-transparent font-bold placeholder:text-gray-300"
                                                        placeholder="..." {{ $isFormTeacherOnly ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Affective Domain --}}
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
                                                <td class="w-1/3 border-l border-black p-0">
                                                    <input type="text" form="studentSheetCommentForm" name="affective[{{ $trait }}]"
                                                        value="{{ $affectiveData[$trait] ?? '' }}"
                                                        class="w-full h-full border-none focus:ring-0 text-center uppercase text-[12px] bg-transparent font-bold placeholder:text-gray-300"
                                                        placeholder="..." {{ $isFormTeacherOnly ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Grading Summary --}}
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
                                    <!-- Grades Interpretation Header -->
                                    <tr>
                                        <td colspan="2"
                                            class="border border-black text-center font-bold bg-gray-50 py-1 uppercase italic tracking-wide text-[#0826d8]">
                                            Grades Interpretation
                                        </td>
                                    </tr>
                                    <!-- Grade Rows -->
                                    @if($classType === 'ss')
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
                                    @elseif($classType === 'js')
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

                        {{-- Teacher/Principal Comments --}}
                        <div
                            class="flex flex-col md:flex-row gap-8 mt-4 items-start md:items-end justify-between text-blue-600 font-bold">
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
                                        {{ $generatedSchoolHeadComment ?? 'Remark pending...' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Signature Section --}}
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

                        {{-- Footer Timestamp --}}
                        <div
                            class="mt-8 border-t border-gray-300 pt-2 flex justify-between items-center text-[10px] text-gray-500 italic">
                            <span>Result generated via Bezaleel School Portal</span>
                            <span>Date: {{ now()->format('d M Y (H:i)') }}</span>
                        </div>
                    </div>

                    {{-- Interactive Components (Admin/Form Teacher Only) --}}
                    @if((auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && $results->isNotEmpty())
                        <div class="max-w-[1100px] mx-auto mt-10 p-4 sm:p-6 bg-white border border-blue-200 rounded-xl shadow-lg no-print">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Manage Teacher Comments</h2>
                                    <p class="text-sm text-gray-500">Add or update the form teacher's feedback for this student.</p>
                                </div>
                            </div>

                            <form id="studentSheetCommentForm" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Form Teacher Section -->
                                    <div
                                        class="p-4 border border-blue-100 rounded-lg bg-blue-50/30 {{ $isAdminActor ? '' : 'md:col-span-2' }}">
                                        <h3 class="text-blue-900 font-bold uppercase text-xs mb-3 flex items-center">
                                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                            Form Teacher Assessment
                                        </h3>
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <div class="w-full md:w-1/2 space-y-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Comment</label>
                                                    <textarea name="form_teacher_comment" rows="3"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                                        placeholder="Enter teacher's comment...">{{ old('form_teacher_comment', $results->first()->form_teacher_comment ?? '') }}</textarea>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Signature</label>
                                                    <div
                                                        class="border-2 border-dashed border-gray-300 bg-white rounded flex flex-col items-center p-1">
                                                        <canvas id="form-teacher-canvas" class="w-full h-24 cursor-crosshair"></canvas>
                                                        <div class="w-full h-[1px] bg-gray-200 mt-1"></div>
                                                        <button type="button" onclick="clearFP()"
                                                            class="mt-1 text-[10px] text-red-500 hover:text-red-700 font-bold uppercase transition-colors">Clear
                                                            Signature</button>
                                                    </div>
                                                    <input type="hidden" name="form_teacher_signature" id="form_teacher_signature_input">
                                                </div>
                                            </div>
                                            <div class="w-full md:w-1/2 space-y-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Next Term
                                                        Fees</label>
                                                    <input type="text" name="next_term_fees"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                                        placeholder="e.g. 25,000"
                                                        value="{{ old('next_term_fees', $results->first()->next_term_fees ?? '') }}">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Next Term
                                                        Begins</label>
                                                    <input type="text" name="next_term_begins"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                                        placeholder="e.g. 15th September, 2026"
                                                        value="{{ old('next_term_begins', $results->first()->next_term_begins ?? '') }}">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">No. of times
                                                        school
                                                        opened</label>
                                                    <input type="number" name="attendance_opened" min="0"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm"
                                                        placeholder="e.g. 95" value="{{ old('attendance_opened', $attendanceOpened) }}">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">No. of
                                                        attendance</label>
                                                    <input type="number" name="attendance_present" min="0"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm"
                                                        placeholder="e.g. 87"
                                                        value="{{ old('attendance_present', $results->first()->attendance_present ?? 0) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($isAdminActor)
                                        <!-- School Head Section -->
                                        <div class="p-4 border border-blue-100 rounded-lg bg-blue-50/30">
                                            <h3 class="text-blue-900 font-bold uppercase text-xs mb-3 flex items-center">
                                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                                Principal's Remark
                                            </h3>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Remark</label>
                                                    <textarea name="school_head_comment" rows="3"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                                        placeholder="Enter principal's remark...">{{ old('school_head_comment', $results->first()->school_head_comment ?? '') }}</textarea>
                                                </div>
                                                @if(!$isFormTeacherOnly)
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Next Term
                                                            Begins</label>
                                                        <input type="text" name="next_term_begins"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                                            placeholder="e.g. 15th September, 2026"
                                                            value="{{ old('next_term_begins', $results->first()->next_term_begins ?? '') }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Next Term
                                                            Fees</label>
                                                        <input type="text" name="next_term_fees"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                                            placeholder="e.g. 25,000"
                                                            value="{{ old('next_term_fees', $results->first()->next_term_fees ?? '') }}">
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between border-t border-blue-100 pt-6">
                                    <div id="commentStatus"
                                        class="hidden sm:flex items-center space-x-2 text-sm font-semibold transition-opacity duration-300 opacity-0">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-600">Saved successfully!</span>
                                    </div>
                                    <button type="submit" id="saveCommentBtn"
                                        class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 active:transform active:scale-95 transition-all shadow-md">
                                        Update Report Card Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    @elseif((auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && $results->isEmpty())
                        <div
                            class="max-w-[1100px] mx-auto mt-10 p-8 bg-amber-50 border border-amber-200 rounded-xl shadow-lg no-print flex flex-col items-center text-center">
                            <div class="p-4 bg-amber-100 rounded-full mb-4">
                                <i class="fas fa-exclamation-circle text-3xl text-amber-600"></i>
                            </div>
                            <h2 class="text-xl font-black text-amber-900 uppercase tracking-wide">Action Required: Record Subject Scores
                            </h2>
                            <p class="text-amber-800 mt-2 max-w-md">Teacher comments and signatures can only be added once at least one
                                subject score has been recorded for this student.</p>
                            <!-- <a href="{{ route('teacher.score-sheet') }}"
                                                                                                                                                class="mt-6 px-10 py-3 bg-black px-2 text-white font-black rounded-lg hover:bg-amber-700 transition-all shadow-md uppercase tracking-widest text-xs">
                                                                                                                                                Go to Score Sheet
                                                                                                                                            </a> -->
                        </div>
                    @endif

                    {{-- Admin Approval Controls --}}
                    @if((auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && $results->isNotEmpty())
                        @php
                            $isSheetApproved = $results->every(fn($r) => $r->is_approved);
                        @endphp
                        <div class="max-w-[1100px] mx-auto mt-6 p-4 sm:p-6 bg-white border border-green-200 rounded-xl shadow-lg no-print">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-start sm:items-center space-x-3">
                                    <div class="p-2 {{ $isSheetApproved ? 'bg-green-100' : 'bg-yellow-100' }} rounded-full">
                                        <i
                                            class="fas {{ $isSheetApproved ? 'fa-check text-green-600' : 'fa-clock text-yellow-600' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Result Sheet Status:
                                            {{ $isSheetApproved ? 'Approved' : 'Pending' }}
                                        </h2>
                                        <p class="text-sm text-gray-500">Manage the approval status for all subjects in this term for the
                                            student.</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                    @if(!$isSheetApproved)
                                        <form method="POST"
                                            action="{{ route('result.approve-sheet', ['studentId' => $student->id, 'termId' => $term->id]) }}">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to approve all subjects for this student?')"
                                                class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 active:transform active:scale-95 transition-all shadow-md">
                                                Approve Result Sheet
                                            </button>
                                        </form>
                                    @endif
                                    @if($isSheetApproved)
                                        <form method="POST"
                                            action="{{ route('result.disapprove-sheet', ['studentId' => $student->id, 'termId' => $term->id]) }}">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to disapprove all subjects for this student?')"
                                                class="w-full sm:w-auto px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 active:transform active:scale-95 transition-all shadow-md">
                                                Disapprove Result Sheet
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Print Controls --}}
                    <div class="max-w-[1100px] mx-auto mt-6 mb-10 text-center no-print">
                        <button onclick="window.print()"
                            class="px-6 py-4 bg-gray-900 text-white font-bold rounded-full hover:bg-black transition-all shadow-xl flex items-center space-x-2 mx-auto uppercase tracking-widest text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            <span>Print Report Card</span>
                        </button>
                    </div>
                </div>

                <style>
                    @media print {
                        body {
                            background: white !important;
                            margin: 0;
                            padding: 0;
                        }

                        .no-print {
                            display: none !important;
                        }

                        .dashboard-wrapper {
                            padding: 0 !important;
                        }

                        .min-h-screen {
                            min-height: 0 !important;
                            py: 0 !important;
                        }

                        .shadow-sm,
                        .shadow-lg,
                        .shadow-xl {
                            box-shadow: none !important;
                        }

                        .max-w-[1100px] {
                            max-width: 100% !important;
                            border: 1px solid black !important;
                        }

                        @page {
                            margin: 1cm;
                            size: portrait;
                        }

                        input::placeholder {
                            color: transparent !important;
                        }
                    }
                </style>

                <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
                @if((auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && $results->isNotEmpty())
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Initialize Signature Pads
                            const ftCanvas = document.getElementById('form-teacher-canvas');

                            const ftPad = new SignaturePad(ftCanvas, { backgroundColor: 'rgb(255, 255, 255)' });

                            function resizeCanvas(canvas, pad) {
                                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width = canvas.offsetWidth * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext("2d").scale(ratio, ratio);
                                pad.clear();
                            }

                            const handleResize = () => {
                                resizeCanvas(ftCanvas, ftPad);

                            };

                            window.addEventListener("resize", handleResize);
                            handleResize();

                            window.clearFP = () => ftPad.clear();


                            const form = document.getElementById('studentSheetCommentForm');
                            if (!form) return;

                            form.addEventListener('submit', function (e) {
                                e.preventDefault();

                                // Populate Hidden Inputs with Signature Data
                                if (!ftPad.isEmpty()) {
                                    document.getElementById('form_teacher_signature_input').value = ftPad.toDataURL("image/png");
                                }


                                const btn = document.getElementById('saveCommentBtn');
                                const status = document.getElementById('commentStatus');

                                btn.disabled = true;
                                btn.innerHTML = '<span>Saving Record...</span>';

                                const formData = new FormData(this);
                                fetch(`{{ route('result.add-comment', $results->first()) }}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                        'Accept': 'application/json'
                                    },
                                    body: formData
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            if (typeof window.showToast === 'function') {
                                                window.showToast(data.message || 'Record updated successfully.', 'success');
                                            } else {
                                                alert(data.message || 'Record updated successfully.');
                                            }
                                            status.classList.remove('opacity-0');
                                            status.classList.add('opacity-100');
                                            setTimeout(() => {
                                                status.classList.remove('opacity-100');
                                                status.classList.add('opacity-0');
                                                window.location.reload();
                                            }, 1000);
                                        } else {
                                            if (typeof window.showToast === 'function') {
                                                window.showToast(data.message || 'Failed to update record.', 'error');
                                            } else {
                                                alert('Error: ' + data.message);
                                            }
                                            btn.disabled = false;
                                            btn.innerHTML = 'Update Report Card Details';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        if (typeof window.showToast === 'function') {
                                            window.showToast('Failed to save record. Please try again.', 'error');
                                        } else {
                                            alert('Failed to save record. Please try again.');
                                        }
                                        btn.disabled = false;
                                        btn.innerHTML = 'Update Report Card Details';
                                    });
                            });
                        });
                    </script>
                @endif
@endsection
