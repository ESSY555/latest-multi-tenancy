                                        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white p-6 text-[12px] text-gray-900">
    @php
$canManageMockSheet = auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin;
    @endphp
    @if(session('success'))
        <div class="max-w-[1100px] mx-auto mb-4 no-print bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->has('error'))
        <div class="max-w-[1100px] mx-auto mb-4 no-print bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ $errors->first('error') }}
        </div>
    @endif
    <div class="mock-result-sheet max-w-[1100px] mx-auto border-4 border-[#283070] p-5 relative overflow-hidden">
        <div class="mb-5">
            <div class="relative text-[#283070] min-h-[120px]">
                <div class="absolute left-0 top-0 w-[86px] h-[98px]">
                    <img src="{{ asset('/images/bezalee-logo-main.PNG') }}" alt="school logo"
                        class="w-full h-full object-contain">
                </div>
    
                <div class="mx-[92px] text-center leading-tight mb-5">
                    <h1 class="text-[23px] font-black tracking-[0.8px] uppercase">BEZALEEL INTERNATIONAL SCHOOL</h1>
                    <h2 class="text-[15px] font-black tracking-[1.2px] uppercase -mt-0.5">MPAPE ABUJA</h2>
                    <p class="text-[11px] italic font-semibold mt-0.5">MOTTO: Towards Excellence</p>
                    <p class="text-[11px] font-bold mt-0.5">TELEPHONE: 07011731515 &nbsp; 08052123760</p>
                    <p class="text-[27px] leading-none font-bold text-red-600 underline underline-offset-2 mt-1.5">MOCK</p>
                </div>
    
                <div class="absolute right-0 top-[54px] w-[305px] leading-[1.06] text-left">
                    <p class="text-[14px] font-black uppercase tracking-[0.7px]">CONTINUOUS ASSESSMENT DOSSIER</p>
                    <p class="text-[14px] font-black uppercase tracking-[0.7px]">FOR SENIOR SECONDARY SCHOOL</p>
                </div>
            </div>
    
            <div class="mt-2 grid grid-cols-2 gap-x-6 gap-y-3 text-[12px] font-semibold uppercase">
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">Name:</span>
                    <span class="flex-1 border-b border-gray-700 h-4 truncate">{{ $student->name ?? '' }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">Admission Number:</span>
                    <span
                        class="flex-1 border-b border-gray-700 h-4 truncate">{{ $student->studentProfile->admission_number ?? '' }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">Term:</span>
                    <span
                        class="flex-1 border-b border-gray-700 h-4 leading-4 px-1 truncate whitespace-nowrap">{{ $mockExam->name ?? 'MOCK' }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">Class:</span>
                    <span
                        class="flex-1 border-b border-gray-700 h-4 leading-4 px-1 truncate whitespace-nowrap">{{ $firstResult?->schoolClass?->name ?? $student->enrollments->first()?->schoolClass?->name ?? '' }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">No. of times school opened:</span>
                    <span
                        class="flex-1 border-b border-gray-700 h-4">{{ $firstResult?->attendance_present ? $firstResult->attendance_present + $firstResult->attendance_absent + $firstResult->attendance_late : '' }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">No. of attendance:</span>
                    <span class="flex-1 border-b border-gray-700 h-4">{{ $firstResult?->attendance_present ?? '' }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">Total average score:</span>
                    <span class="flex-1 border-b border-gray-700 h-4">{{ number_format($averageScore ?? 0, 2) }}</span>
                </div>
                <div class="flex items-end gap-1 min-w-0">
                    <span class="whitespace-nowrap">Next term begins:</span>
                    <span
                        class="flex-1 border-b border-gray-700 h-4 leading-4 px-1 truncate">{{ $firstResult?->next_term_begins ?? '' }}</span>
                </div>
            </div>
        </div>
    
        <div class="relative mt-4 overflow-hidden">
            <x-result-watermark />
            <table class="relative z-10 w-full border-collapse border border-gray-800 text-[11px]">
                <thead>
                    <tr>
                        <th class="border border-gray-800 p-1 w-[60px]">S/N</th>
                        <th class="border border-gray-800 p-1 text-left pl-2 w-[220px]">SUBJECTS</th>
                        <th class="border border-gray-800 p-1">TEST</th>
                        <th class="border border-gray-800 p-1">PRACTICAL</th>
                        <th class="border border-gray-800 p-1">EXAM</th>
                        <th class="border border-gray-800 p-1">TERM TOTAL</th>
                        <th class="border border-gray-800 p-1">CLASS AVERAGE</th>
                        <th class="border border-gray-800 p-1">HIGHEST IN CLASS</th>
                        <th class="border border-gray-800 p-1">LOWEST IN CLASS</th>
                        <th class="border border-gray-800 p-1">GRADE</th>
                        <th class="border border-gray-800 p-1">POSITION</th>
                        <th class="border border-gray-800 p-1">REMARK</th>
                        <!-- <th class="border border-gray-800 p-1">SUBJECT TEACHER SIGNATURE</th> -->
                        @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                            <th class="border border-gray-800 p-1 no-print">ACTIONS</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach(($results ?? collect()) as $index => $result)
                        <tr>
                            <td class="border border-gray-800 p-1 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-800 p-1 text-left pl-2">{{ $result->subject->name ?? '-' }}</td>
                            <td class="border border-gray-800 p-1 text-center">{{ number_format($result->ca1 ?? 0, 0) }}</td>
                            <td class="border border-gray-800 p-1 text-center">{{ number_format($result->ca2 ?? 0, 0) }}</td>
                            <td class="border border-gray-800 p-1 text-center">{{ number_format($result->exam, 0) }}</td>
                            <td class="border border-gray-800 p-1 text-center">{{ number_format($result->total ?? 0, 0) }}</td>
                            <td class="border border-gray-800 p-1 text-center">
                                {{ number_format((float) ($subjectStats[$result->subject_id]->class_average ?? 0), 2) }}
                            </td>
                            <td class="border border-gray-800 p-1 text-center">
                                {{ number_format((float) ($subjectStats[$result->subject_id]->highest_in_class ?? 0), 0) }}
                            </td>
                            <td class="border border-gray-800 p-1 text-center">
                                {{ number_format((float) ($subjectStats[$result->subject_id]->lowest_in_class ?? 0), 0) }}
                            </td>
                            <td class="border border-gray-800 p-1 text-center">{{ $result->grade ?? '-' }}</td>
                            <td class="border border-gray-800 p-1 text-center">
                                {{ $result->position ? $result->position . ((($result->position % 100) >= 11 && ($result->position % 100) <= 13) ? 'th' : (['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][$result->position % 10])) : '-' }}
                            </td>
                            <td class="border border-gray-800 p-1.5 text-[10px] leading-tight">{{ $result->remark ?? '-' }}</td>
                            <!-- <td class="border border-gray-800 p-1 h-10">
                                @if($result->form_teacher_signature)
                                    <img src="{{ asset('storage/' . $result->form_teacher_signature) }}" alt="signature"
                                        style="max-width: 70px; max-height: 40px;" class="mx-auto object-contain">
                                @endif
                            </td> -->
                            @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                                <td class="border border-gray-800 p-1 text-center no-print">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('result.mock-edit', $result) }}"
                                            class="px-2 py-1 bg-blue-600 text-white rounded text-[10px] font-bold hover:bg-blue-700">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('result.mock-destroy', $result) }}"
                                            onsubmit="return confirm('Delete {{ $result->subject->name ?? 'this' }} mock result? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 bg-red-600 text-white rounded text-[10px] font-bold hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
    
    
    
        <!-- Flexible Grid Sections -->
        <div class="grid grid-cols-3 gap-8 py-8 items-start">
            {{-- Psychomotor --}}
            <div class="w-full">
                <h2 class="text-[12px] font-bold uppercase text-center mb-1 tracking-wide">Psychomotor</h2>
                <table class="w-full border border-black border-collapse table-fixed text-[10px] uppercase">
                    <thead>
                        <tr>
                            <th class="border border-black p-1 w-[52%]"></th>
                            <th class="border border-black p-1 w-[16%] text-center">0</th>
                            <th class="border border-black p-1 w-[16%] text-center">5</th>
                            <th class="border border-black p-1 w-[16%] text-center">10</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
$psychomotorData = $firstResult?->psychomotor ?? [];
                        @endphp
                        <tr>
                            <td class="border border-black p-1 font-semibold">Handwriting</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="psychomotor[Handwriting]" value="{{ $psychomotorData['Handwriting'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Fluency</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="psychomotor[Fluency]" value="{{ $psychomotorData['Fluency'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Sports</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="psychomotor[Participation in Sports]"
                                    value="{{ $psychomotorData['Participation in Sports'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Neatness</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="psychomotor[Handling Tools (Practical)]"
                                    value="{{ $psychomotorData['Handling Tools (Practical)'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Music Skills</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="psychomotor[Music/Performance Skill]"
                                    value="{{ $psychomotorData['Music/Performance Skill'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-bold">Total</td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    
            {{-- Affective --}}
            <div class="w-full">
                <h2 class="text-[12px] font-bold uppercase text-center mb-1 tracking-wide">Affective</h2>
                <table class="w-full border border-black border-collapse table-fixed text-[10px] uppercase">
                    <thead>
                        <tr>
                            <th class="border border-black p-1 w-[52%]"></th>
                            <th class="border border-black p-1 w-[16%] text-center">0</th>
                            <th class="border border-black p-1 w-[16%] text-center">5</th>
                            <th class="border border-black p-1 w-[16%] text-center">10</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
$affectiveData = $firstResult?->affective ?? [];
                        @endphp
                        <tr>
                            <td class="border border-black p-1 font-semibold">Honesty</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="affective[General Conduct]" value="{{ $affectiveData['General Conduct'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Spiritual Development</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="affective[Spiritual Development]"
                                    value="{{ $affectiveData['Spiritual Development'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Neatness</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="affective[Neatness/Cleanliness]"
                                    value="{{ $affectiveData['Neatness/Cleanliness'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Punctuality</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="affective[Punctuality]" value="{{ $affectiveData['Punctuality'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-semibold">Reliability</td>
                            <td class="border border-black text-center"><input form="mockSheetCommentForm" type="text"
                                    name="affective[Reliability]" value="{{ $affectiveData['Reliability'] ?? '' }}"
                                    @disabled(!$canManageMockSheet)
                                    class="w-full text-center border-none focus:ring-0 text-[10px] bg-transparent disabled:text-gray-700">
                            </td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1 font-bold">Total</td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    
            {{-- Result Summary --}}
            <div class="w-full">
                <table class="w-full border border-black border-collapse table-fixed text-[10px] uppercase">
                    <tbody>
                        <tr>
                            <td class="border border-black p-1.5 font-semibold w-[62%]">Total Mark Obtainable</td>
                            <td class="border border-black p-1.5 text-center">{{ ($totalSubjects ?? 0) * 100 }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1.5 font-semibold">Total Mark Obtained</td>
                            <td class="border border-black p-1.5 text-center">{{ number_format($totalScore ?? 0, 0) }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1.5 font-semibold">Average</td>
                            <td class="border border-black p-1.5 text-center">{{ number_format($averageScore ?? 0, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black p-1.5 text-center font-bold">Grades Interpretation
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">84-100 (PASS) D7</td>
                            <td class="border border-black p-1 text-center">45-50 (PASS) E8</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">74-100 (Excellent) A1</td>
                            <td class="border border-black p-1 text-center">0-44 (Fail) F9</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">69-75 (Very Good) B2</td>
                            <td class="border border-black p-1 text-center"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">60-74 (Good) B3</td>
                            <td class="border border-black p-1 text-center"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">55-60 (Credit) C4</td>
                            <td class="border border-black p-1 text-center"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">50-55 (Credit) C5</td>
                            <td class="border border-black p-1 text-center"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1">45-50 (Credit) C6</td>
                            <td class="border border-black p-1 text-center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
    
    
    
        <div class="mt-7 space-y-8 text-[12px]">
            <!-- Comment Lines -->
            <div class="flex items-end gap-2">
                <span class="font-bold uppercase whitespace-nowrap">Class Teacher's Remark</span>
                <span class="flex-1 border-b border-gray-700 h-5">{{ $firstResult?->form_teacher_comment ?? '' }}</span>
            </div>
    
            <div class="flex items-end gap-2">
                <span class="font-bold uppercase whitespace-nowrap">Principal</span>
                <span class="flex-1 border-b border-gray-700 h-5">{{ $generatedSchoolHeadComment ?? '' }}</span>
            </div>
    
            <!-- Signature Lines -->
            <!--
            <div class="grid grid-cols-2 gap-20 px-8 mt-16 mb-8 text-center text-sm print:break-inside-avoid">
                <div class="space-y-1">
                    <div class="border-b-2 border-black pb-1 h-14 flex items-end justify-center">
                        @if($firstResult?->form_teacher_signature)
                            <img src="{{ asset('storage/' . $firstResult->form_teacher_signature) }}"
                                alt="Class teacher signature" class="h-12 object-contain">
                        @endif
                    </div>
                    <p class="font-black uppercase tracking-wide text-[#283070] mt-1">Class Teacher's Signature</p>
                </div>
                <div class="space-y-1">
                    <div class="border-b-2 border-black pb-1 h-14 flex items-end justify-center">
                        @if($firstResult?->school_head_signature)
                            <img src="{{ asset('storage/' . $firstResult->school_head_signature) }}"
                                alt="Principal signature and stamp" class="h-12 object-contain">
                        @endif
                    </div>
                    <p class="font-black uppercase tracking-wide text-[#283070] mt-1">Principal's Signature &amp; Stamp</p>
                </div>
            </div>
            -->
        </div>
    
        @if((auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && ($results ?? collect())->isNotEmpty())
            <div class="max-w-[1100px] mx-auto mt-10 p-6 bg-white border border-blue-200 rounded-xl shadow-lg no-print">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-2 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Manage Mock Sheet Comments</h2>
                        <p class="text-sm text-gray-500">Add or update form teacher and principal feedback for this mock result.
                        </p>
                    </div>
                </div>

                <form id="mockSheetCommentForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-4 border border-blue-100 rounded-lg bg-blue-50/30">
                            <h3 class="text-blue-900 font-bold uppercase text-xs mb-3">Form Teacher Assessment</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Comment</label>
                                    <textarea name="form_teacher_comment" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                        placeholder="Enter teacher comment...">{{ old('form_teacher_comment', $firstResult->form_teacher_comment ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Signature</label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 bg-white rounded flex flex-col items-center p-1">
                                        <canvas id="mock-ft-canvas" class="w-full h-24 cursor-crosshair"></canvas>
                                        <div class="w-full h-[1px] bg-gray-200 mt-1"></div>
                                        <button type="button" onclick="clearMockFT()"
                                            class="mt-1 text-[10px] text-red-500 hover:text-red-700 font-bold uppercase">Clear
                                            Signature</button>
                                    </div>
                                    <input type="hidden" name="form_teacher_signature" id="mock_ft_sig_input">
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-blue-100 rounded-lg bg-blue-50/30">
                            <h3 class="text-blue-900 font-bold uppercase text-xs mb-3">Principal's Remark</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Remark</label>
                                    <textarea name="school_head_comment" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                        placeholder="Enter principal remark...">{{ old('school_head_comment', $generatedSchoolHeadComment ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Next Term
                                        Begins</label>
                                    <input type="text" name="next_term_begins"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                        value="{{ old('next_term_begins', $firstResult->next_term_begins ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Next Term
                                        Fees</label>
                                    <input type="text" name="next_term_fees"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                        value="{{ old('next_term_fees', $firstResult->next_term_fees ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">No. of times school
                                        opened</label>
                                    <input type="number" name="attendance_opened" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm"
                                        value="{{ old('attendance_opened', ($firstResult?->attendance_present ?? 0) + ($firstResult?->attendance_absent ?? 0) + ($firstResult?->attendance_late ?? 0)) }}">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">No. of
                                        attendance</label>
                                    <input type="number" name="attendance_present" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm"
                                        value="{{ old('attendance_present', $firstResult->attendance_present ?? 0) }}">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Signature</label>
                                    <p class="text-[10px] text-gray-500 mb-1">Principal signs once per mock exam for all
                                        students in this branch.</p>
                                    <div
                                        class="border-2 border-dashed border-gray-300 bg-white rounded flex flex-col items-center p-1">
                                        <canvas id="mock-sh-canvas" class="w-full h-24 cursor-crosshair"></canvas>
                                        <div class="w-full h-[1px] bg-gray-200 mt-1"></div>
                                        <button type="button" onclick="clearMockSH()"
                                            class="mt-1 text-[10px] text-red-500 hover:text-red-700 font-bold uppercase">Clear
                                            Signature</button>
                                    </div>
                                    <input type="hidden" name="school_head_signature" id="mock_sh_sig_input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-blue-100 pt-6">
                        <div id="mockCommentStatus" class="text-sm font-semibold opacity-0 transition-opacity text-green-600">
                            Mock result details saved successfully!
                        </div>
                        <button type="submit" id="mockSaveBtn"
                            class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md">
                            Update Mock Result Details
                        </button>
                    </div>
                </form>
            </div>
        @endif
    
        @if((auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && ($results ?? collect())->isNotEmpty())
            @php $isMockSheetApproved = $results->every(fn($r) => $r->is_approved); @endphp
            <div class="max-w-[1100px] mx-auto mt-6 p-6 bg-white border border-green-200 rounded-xl shadow-lg no-print">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Mock Sheet Status:
                            {{ $isMockSheetApproved ? 'Approved' : 'Pending' }}</h2>
                        <p class="text-sm text-gray-500">Manage approval status for this student's mock sheet.</p>
                    </div>
                    <div class="flex gap-3">
                        @if(!$isMockSheetApproved)
                            <form method="POST"
                                action="{{ route('result.mock-approve-sheet', ['studentId' => $student->id, 'mockExamId' => $mockExam->id]) }}">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">Approve Mock
                                    Sheet</button>
                            </form>
                        @else
                            <form method="POST"
                                action="{{ route('result.mock-disapprove-sheet', ['studentId' => $student->id, 'mockExamId' => $mockExam->id]) }}">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">Disapprove Mock
                                    Sheet</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    
        <div class="max-w-[1100px] mx-auto mt-6 mb-10 text-center no-print">
            <button onclick="window.print()"
                class="px-6 py-4 bg-gray-900 text-white font-bold rounded-full hover:bg-black transition-all shadow-xl uppercase tracking-widest text-sm">
                Print Report Card
            </button>
        </div>
    
    
    
    
    </div>
    
    <style>
        .mock-result-sheet {
            color: #283070;
        }
    
        .mock-result-sheet .border-gray-800,
        .mock-result-sheet .border-gray-700,
        .mock-result-sheet .border-gray-600,
        .mock-result-sheet .border-black {
            border-color: #283070 !important;
        }
    
        .mock-result-sheet .text-gray-900,
        .mock-result-sheet .text-gray-700,
        .mock-result-sheet .text-black {
            color: #283070 !important;
        }
    
        .mock-result-sheet .disabled\:text-gray-700:disabled {
            color: #283070 !important;
        }

        @media print {
            .no-print { display: none !important; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    @if((auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && ($results ?? collect())->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ftCanvas = document.getElementById('mock-ft-canvas');
                const shCanvas = document.getElementById('mock-sh-canvas');
                if (!ftCanvas || !shCanvas) return;

                const ftPad = new SignaturePad(ftCanvas, { backgroundColor: 'rgb(255, 255, 255)' });
                const shPad = new SignaturePad(shCanvas, { backgroundColor: 'rgb(255, 255, 255)' });

                function resizeCanvas(canvas, pad) {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    pad.clear();
                }

                const handleResize = () => {
                    resizeCanvas(ftCanvas, ftPad);
                    resizeCanvas(shCanvas, shPad);
                };
                window.addEventListener("resize", handleResize);
                handleResize();

                window.clearMockFT = () => ftPad.clear();
                window.clearMockSH = () => shPad.clear();

                const form = document.getElementById('mockSheetCommentForm');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!ftPad.isEmpty()) {
                        document.getElementById('mock_ft_sig_input').value = ftPad.toDataURL("image/png");
                    }
                    if (!shPad.isEmpty()) {
                        document.getElementById('mock_sh_sig_input').value = shPad.toDataURL("image/png");
                    }

                    const btn = document.getElementById('mockSaveBtn');
                    const status = document.getElementById('mockCommentStatus');
                    btn.disabled = true;
                    btn.innerText = 'Saving...';

                    fetch(`{{ route('result.mock-add-comment', $results->first()) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('#mockSheetCommentForm input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            status.classList.remove('opacity-0');
                            setTimeout(() => window.location.reload(), 900);
                        } else {
                            alert('Error: ' + data.message);
                            btn.disabled = false;
                            btn.innerText = 'Update Mock Result Details';
                        }
                    })
                    .catch(() => {
                        alert('Failed to save mock result details.');
                        btn.disabled = false;
                        btn.innerText = 'Update Mock Result Details';
                    });
                });
            });
        </script>
    @endif
</body>

</html>
