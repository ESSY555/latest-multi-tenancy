@extends('layouts.dashboard')

@section('title', 'Summary of Annual Academic Report - ' . $student->name)

@push('head')
    <style>
        @media print {

            .no-print,
            header,
            aside,
            .md\:hidden {
                display: none !important;
            }

            body {
                padding: 0 !important;
                background: white !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
            }

            .max-w-\[1100px\] {
                border: 1px solid black !important;
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
            }
        }

        canvas {
            touch-action: none;
        }
    </style>
@endpush

@section('dashboard')
    <div class="max-w-[1100px] mx-auto no-print mb-4 flex flex-wrap gap-3 justify-between items-center px-2">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('student.results.annual.index') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold text-xs uppercase hover:bg-gray-200 transition-all flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to History
            </a>

            <!-- <div class="relative group">
                <select onchange="window.location.href=this.value"
                    class="appearance-none bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase cursor-pointer hover:bg-indigo-700 transition-all pr-8 focus:outline-none shadow-md max-w-[200px]">
                    @foreach($sessions as $session)
                        <option value="{{ route('student.results.annual', [$student->id, $session->id]) }}" {{ $session->id == $academicYear->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-white/70">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div> -->
        </div>

        @if((auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && (!$annualSummary || !$annualSummary->is_approved))
            <button id="approveBtn" onclick="approveReport()"
                class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold text-xs uppercase hover:bg-green-700 transition-all shadow-md flex items-center">
                <i class="fas fa-check-circle mr-2"></i> Approve Now
            </button>
        @endif
    </div>

    <div class="max-w-[1100px] mx-auto border border-gray-800 p-5 relative overflow-hidden bg-white shadow-sm">
        <x-result-watermark />
        @if($annualSummary && $annualSummary->is_approved)
            <div
                class="absolute -right-12 -top-12 w-40 h-40 bg-green-50 text-green-600 border-4 border-green-200 rounded-full flex items-center justify-center rotate-12 opacity-30 z-0 pointer-events-none">
                <span class="font-black text-2xl border-4 border-green-600 p-2 rounded transform scale-125">APPROVED</span>
            </div>
        @endif

        <div class="relative">
            <div class="relative text-center mb-4 px-[68px] sm:px-[88px]">
                <div class="absolute left-0 top-0 w-[60px] h-[60px] sm:w-[80px] sm:h-[80px] border border-gray-800">
                    <img src="{{ asset('/images/bezalee-logo-main.PNG') }}" alt="" class="w-full h-full object-cover">
                </div>
                <h1 class="text-sm sm:text-2xl md:text-3xl font-bold text-blue-600 leading-tight break-words">BEZALEEL  <br class="sm:hidden">INTERNATIONAL SCHOOL</h1>
                <h2 class="text-sm sm:text-xl md:text-2xl font-semibold text-blue-600 font-bold">MPAPE ABUJA</h2>
                <p class="text-xs sm:text-sm text-red-600 font-bold">Motto: Towards Excellence</p>
                  <div class="mt-2">
                                    <p class="font-bold text-red-600 text-xs font-bold">TELEPHONE:<span class="font-bold">07014907969,</span>
                                        <span class=" font-bold">08052123760</span>
                                    </p>
                                </div>
                <h3 class="text-xs sm:text-base md:text-xl font-semibold text-blue-600 font-bold">SUMMARY OF ANNUAL ACADEMIC REPORT</h3>
            </div>

            <div
                class="absolute right-0 top-0 w-[60px] h-[60px] sm:w-[80px] sm:h-[80px] border border-gray-800 bg-gray-50 flex items-center justify-center overflow-hidden">
                @if($student->profile_photo)
                    <img src="{{ asset('uploads/profile-photos/' . $student->profile_photo) }}" alt="student profile image"
                        class="w-full h-full object-cover">
                @else
                    <div class="text-[10px] text-gray-400 font-bold text-center p-1 uppercase leading-tight italic">
                        Passport<br>Photo
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 my-5">
            <div class="space-y-3">
                <div class="flex"><span class="min-w-[140px] font-semibold">SESSION:</span><span
                        class="flex-1 border-b border-gray-800 font-bold">{{ $academicYear->name }}</span></div>
                <div class="flex"><span class="min-w-[140px] font-semibold">STUDENT NAME:</span><span
                        class="flex-1 border-b border-gray-800 font-bold">{{ $student->name }}</span></div>
                <div class="flex"><span class="min-w-[140px] font-semibold">CLASS:</span><span
                        class="flex-1 border-b border-gray-800 font-bold">{{ $schoolClass->name ?? 'N/A' }}</span>
                </div>
                <div class="flex"><span class="min-w-[140px] font-semibold">ADMISSION NO:</span><span
                        class="flex-1 border-b border-gray-800 font-bold">{{ $student->studentProfile?->admission_number ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex"><span class="min-w-[140px] font-semibold">NEXT TERM BEGINS:</span><span
                        class="flex-1 border-b border-gray-800"></span></div>
                <div class="flex"><span class="min-w-[140px] font-semibold">TOTAL AVERAGE:</span><span
                        class="flex-1 border-b border-gray-800 text-center font-bold">{{ round($totalAverage, 2) }}%</span>
                </div>
                <div class="flex"><span class="min-w-[140px] font-semibold">NO OF TIMES SCHOOL OPENED:</span><span
                        class="flex-1 border-b border-gray-800 text-center font-bold">
                        {{ $annualSummary?->number_of_times_school_opened ?? '' }}
                    </span></div>
                <div class="flex"><span class="min-w-[140px] font-semibold">PROMOTED/NOT PROMOTED:</span><span
                        class="flex-1 border-b border-gray-800 text-center font-bold uppercase">{{ str_replace('_', ' ', $promotionStatus) }}</span>
                </div>
                <div class="flex"><span class="min-w-[140px] font-semibold">PASS / FAIL:</span><span
                        class="flex-1 border-b border-gray-800 text-center font-bold uppercase">{{ $passStatus }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto -mx-1">
        <table class="w-full border-collapse border border-gray-800 mt-4 text-[14px] text-blue-600 font-bold" style="min-width:600px">
            <thead>
                <tr>
                    <th class="border border-gray-800 p-1 w-[60px]">S/N</th>
                    <th class="border border-gray-800 p-1 text-left pl-2 w-[220px]">SUBJECTS</th>
                    <th class="border border-gray-800 p-1">1ST TERM</th>
                    <th class="border border-gray-800 p-1">2ND TERM</th>
                    <th class="border border-gray-800 p-1">3RD TERM</th>
                    <th class="border border-gray-800 p-1">AVG</th>
                    <th class="border border-gray-800 p-1">GRADE</th>
                    <th class="border border-gray-800 p-1">POSITION</th>
                    <th class="border border-gray-800 p-1">TEACHER'S REMARK</th>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                        <th class="border border-gray-800 p-1 no-print">ACTIONS</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $count = 1; @endphp
                @foreach($subjectResults as $res)
                    <tr class="text-blue-600 font-bold">
                        <td class="border border-gray-800 p-1 text-center">{{ $count++ }}</td>
                        <td class="border border-gray-800 p-1 text-left pl-2">{{ $res['subject'] }}</td>
                        <td class="border border-gray-800 p-1 text-center">{{ $res['term1'] }}</td>
                        <td class="border border-gray-800 p-1 text-center">{{ $res['term2'] }}</td>
                        <td class="border border-gray-800 p-1 text-center">{{ $res['term3'] }}</td>
                        <td class="border border-gray-800 p-1 text-center font-bold">{{ $res['average'] }}</td>
                        <td class="border border-gray-800 p-1 text-center font-bold">{{ $res['grade'] }}</td>
                        <td class="border border-gray-800 p-1 text-center font-bold">{{ $res['position'] }}</td>
                        <td class="border border-gray-800 p-1 text-left pl-1">{{ $res['remark'] }}</td>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
                            <td class="border border-gray-800 p-1 text-center no-print">
                                @if($res['editable_result_id'])
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('result.edit', $res['editable_result_id']) }}"
                                            class="px-2 py-1 bg-blue-600 text-white rounded text-[10px] font-bold hover:bg-blue-700"
                                            title="Edit {{ $res['subject'] }} ({{ $res['editable_term_label'] }})">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('result.destroy', $res['editable_result_id']) }}"
                                            onsubmit="return confirm('Delete {{ $res['subject'] }} {{ $res['editable_term_label'] }} result? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 bg-red-600 text-white rounded text-[10px] font-bold hover:bg-red-700"
                                                title="Delete {{ $res['subject'] }} ({{ $res['editable_term_label'] }})">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] text-gray-400">N/A</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>


        <div class="mt-5 text-xl flex  gap-5 flex-col md:flex-row justify-between">

            <p class="text-blue-600 font-bold">No of Passes <span class="border border-2 w-12 px-2">{{ $noOfPasses }}</span></p>
            <p class="text-blue-600 font-bold">No of Fails <span class="border border-2 w-12 px-2">{{ $noOfFails }}</span></p>


        </div>



        <div class="space-y-5 mt-5">
            <!-- Row 1: Form Master -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                <div class="flex flex-col sm:flex-row sm:items-center flex-1 text-blue-600 font-bold">
                    <span class="sm:min-w-[180px] font-semibold uppercase italic text-blue-600 font-bold text-xs mb-1 sm:mb-0">
                        FORM MASTER'S COMMENT:
                    </span>
                    <span class="flex-1 border-b border-gray-800 px-2 italic font-medium uppercase min-h-[24px]">
                        {{ $annualSummary->form_teacher_comment ?? 'Comment pending...' }}
                    </span>
                </div>
                <div class="text-center flex-shrink-0">
                    <strong class="text-[10px] uppercase text-blue-600 font-bold">FORM MASTER'S SIGNATURE</strong>
                    <div class="border border-gray-800 h-[45px] w-full sm:w-[150px] mt-1 flex items-center justify-center bg-gray-50 overflow-hidden">
                        @if($annualSummary && $annualSummary->form_teacher_signature)
                            <img src="{{ asset('storage/' . $annualSummary->form_teacher_signature) }}" alt="FT Signature"
                                class="h-full object-contain">
                        @else
                            <span class="text-gray-400 text-[10px] uppercase font-bold italic">OFFICIAL SIGN</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Row 2: Principal -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                <div class="flex flex-col sm:flex-row sm:items-center flex-1 text-blue-600 font-bold">
                    <span class="sm:min-w-[180px] font-semibold uppercase italic text-xs text-blue-600 font-bold mb-1 sm:mb-0">
                        PRINCIPAL'S COMMENT:
                    </span>
                    <span class="flex-1 border-b border-gray-800 px-2 italic font-medium uppercase min-h-[24px]">
                        {{ $generatedSchoolHeadComment ?? 'Remark pending...' }}
                    </span>
                </div>
                <div class="text-center flex-shrink-0">
                    <strong class="text-[10px] uppercase text-blue-600 font-bold">PRINCIPAL'S SIGNATURE</strong>
                    <div class="border border-gray-800 h-[45px] w-full sm:w-[150px] mt-1 flex items-center justify-center bg-gray-50 overflow-hidden">
                        @if(!empty($principalSignature))
                            <img src="{{ asset('storage/' . $principalSignature) }}" alt="Principal Signature" class="h-full object-contain">
                        @elseif($annualSummary && $annualSummary->school_head_signature)
                            <img src="{{ asset('storage/' . $annualSummary->school_head_signature) }}" alt="SH Signature"
                                class="h-full object-contain">
                        @else
                            <span class="text-gray-400 text-[10px] uppercase font-bold italic">OFFICIAL SIGN</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Management Section (Staff Only) --}}
    @if(auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin)
        @php
    $isAdminActor = auth()->user()->is_super_admin || auth()->user()->hasRole('admin');
    $canEditAnnualSummary = !($annualSummary && $annualSummary->is_approved) || auth()->user()->is_super_admin;
        @endphp
        <div class="max-w-[1100px] mx-auto mt-10 p-6 bg-white border border-blue-200 rounded-xl shadow-lg no-print font-sans">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Manage Annual Report Feedback</h2>
                    <p class="text-sm text-gray-500">Provide final session assessments and digital signatures.</p>
                </div>
            </div>

            @if(!$canEditAnnualSummary)
                <div class="mb-5 p-3 bg-amber-50 border border-amber-200 rounded text-amber-800 text-sm font-semibold">
                    This annual report has been approved. Only super admin can edit it.
                </div>
            @endif

            <form id="annualSummaryForm" class="space-y-6" method="POST" action="{{ route('student.results.annual.update-summary', [$student->id, $academicYear->id]) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Form Teacher Section -->
                    <div class="p-4 border border-blue-100 rounded-lg bg-blue-50/30">
                        <h3 class="text-blue-900 font-bold uppercase text-xs mb-3 flex items-center">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                            Form Teacher Assessment
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Numbers Of Times School Opened</label>
                                <input type="number" name="number_of_times_school_opened" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm"
                                    placeholder="Enter total number of days"
                                    {{ (!$canEditAnnualSummary || !$isAdminActor) ? 'disabled' : '' }}
                                    value="{{ $annualSummary?->number_of_times_school_opened ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Comment</label>
                                <textarea name="form_teacher_comment" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                    {{ !$canEditAnnualSummary ? 'disabled' : '' }}
                                    placeholder="Enter annual comment...">{{ $annualSummary->form_teacher_comment ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Signature</label>
                                <div
                                    class="border-2 border-dashed border-gray-300 bg-white rounded flex flex-col items-center p-1">
                                    <canvas id="ft-canvas" class="w-full h-24 cursor-crosshair"></canvas>
                                    <div class="w-full h-[1px] bg-gray-200 mt-1"></div>
                                    <button type="button" onclick="ftPad.clear()" {{ !$canEditAnnualSummary ? 'disabled' : '' }}
                                        class="mt-1 text-[10px] text-red-500 hover:text-red-700 font-bold uppercase">Clear</button>
                                </div>
                                <input type="hidden" name="form_teacher_signature" id="ft_sig_input">
                            </div>
                        </div>
                    </div>

                    <!-- Principal Section -->
                    <div class="p-4 border border-blue-100 rounded-lg bg-blue-50/30">
                        <h3 class="text-blue-900 font-bold uppercase text-xs mb-3 flex items-center">
                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                            Principal's remark
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Remark</label>
                                <textarea name="school_head_comment" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                    {{ (!$canEditAnnualSummary || !$isAdminActor) ? 'disabled' : '' }}
                                    placeholder="Enter annual remark...">{{ $generatedSchoolHeadComment ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Promotion
                                    Status</label>
                                @if($isAdminActor)
                                    <select name="promotion_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                        {{ (!$canEditAnnualSummary || !$isAdminActor) ? 'disabled' : '' }}>
                                        <option value="" {{ $promotionStatus === null ? 'selected' : '' }}>
                                            Use computed: {{ str_replace('_', ' ', $promotionStatus) }}
                                        </option>
                                        <option value="promoted" {{ $promotionStatus === 'promoted' ? 'selected' : '' }}>Promoted</option>
                                        <option value="promoted_by_trial" {{ $promotionStatus === 'promoted_by_trial' ? 'selected' : '' }}>Promoted by Trial</option>
                                        <option value="not_promoted" {{ $promotionStatus === 'not_promoted' ? 'selected' : '' }}>Not Promoted</option>
                                        <option value="resit" {{ $promotionStatus === 'resit' ? 'selected' : '' }}>Resit</option>
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">Admin override. Leave blank to keep auto-computed value.</p>
                                @else
                                    <input type="text" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100 text-sm uppercase"
                                        value="{{ str_replace('_', ' ', $promotionStatus) }}">
                                    <p class="text-[10px] text-gray-500 mt-1">Auto-computed from Mathematics, English, and total average.</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Pass Status</label>
                                @if($isAdminActor)
                                    <select name="pass_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 text-sm uppercase"
                                        {{ (!$canEditAnnualSummary || !$isAdminActor) ? 'disabled' : '' }}>
                                        <option value="" {{ $passStatus === null ? 'selected' : '' }}>
                                            Use computed: {{ $passStatus }}
                                        </option>
                                        <option value="pass" {{ $passStatus === 'pass' ? 'selected' : '' }}>Pass</option>
                                        <option value="fail" {{ $passStatus === 'fail' ? 'selected' : '' }}>Fail</option>
                                        <option value="pending" {{ $passStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">Admin override. Leave blank to keep auto-computed value.</p>
                                @else
                                    <input type="text" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100 text-sm uppercase"
                                        value="{{ $passStatus }}">
                                @endif
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Signature</label>
                                <p class="text-[10px] text-gray-500 mb-1">Principal signs once per academic session for all students in this branch.</p>
                                <div
                                    class="border-2 border-dashed border-gray-300 bg-white rounded flex flex-col items-center p-1">
                                    <canvas id="sh-canvas" class="w-full h-24 cursor-crosshair"></canvas>
                                    <div class="w-full h-[1px] bg-gray-200 mt-1"></div>
                                    <button type="button" onclick="shPad.clear()" {{ (!$canEditAnnualSummary || !$isAdminActor) ? 'disabled' : '' }}
                                        class="mt-1 text-[10px] text-red-500 hover:text-red-700 font-bold uppercase">Clear</button>
                                </div>
                                <input type="hidden" name="school_head_signature" id="sh_sig_input">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-blue-100 pt-6">
                    <div id="saveStatus" class="flex items-center space-x-2 text-sm font-semibold opacity-0 transition-opacity">
                        <span class="text-green-600">Report details saved successfully!</span>
                    </div>
                    <button type="submit" id="saveBtn"
                        {{ !$canEditAnnualSummary ? 'disabled' : '' }}
                        class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md">
                        Update Annual Report
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="max-w-[1100px] mx-auto mt-6 mb-10 text-center no-print">
        <a href="{{ route('student.results.annual.print', [$student->id, $academicYear->id]) }}" target="_blank" rel="noopener"
            class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white font-bold rounded-full hover:bg-black shadow-lg uppercase tracking-widest text-xs">
            Print Annual Report
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if((auth()->user()->hasRole('form_teacher') || auth()->user()->hasRole('admin') || auth()->user()->is_super_admin) && $canEditAnnualSummary)
                const ftCanvas = document.getElementById('ft-canvas');
                const shCanvas = document.getElementById('sh-canvas');
                window.ftPad = new SignaturePad(ftCanvas, { backgroundColor: 'rgb(255, 255, 255)' });
                window.shPad = new SignaturePad(shCanvas, { backgroundColor: 'rgb(255, 255, 255)' });

                function resizeCanvases() {
                    [ftCanvas, shCanvas].forEach(canvas => {
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        const ctx = canvas.getContext("2d");
                        ctx.setTransform(1, 0, 0, 1, 0, 0); // Reset transform before scaling
                        ctx.scale(ratio, ratio);
                    });
                }
                window.addEventListener("resize", resizeCanvases);
                resizeCanvases();

                document.getElementById('annualSummaryForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const btn = document.getElementById('saveBtn');
                    const status = document.getElementById('saveStatus');

                    if (!ftPad.isEmpty()) document.getElementById('ft_sig_input').value = ftPad.toDataURL();
                    if (!shPad.isEmpty()) document.getElementById('sh_sig_input').value = shPad.toDataURL();

                    btn.disabled = true;
                    btn.innerText = 'Saving...';

                    fetch("{{ route('student.results.annual.update-summary', [$student->id, $academicYear->id]) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                status.classList.remove('opacity-0');
                                setTimeout(() => {
                                    status.classList.add('opacity-0');
                                    window.location.reload();
                                }, 1500);
                            } else {
                                alert('Error: ' + data.message);
                                btn.disabled = false;
                                btn.innerText = 'Update Annual Report';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('An error occurred. Please try again.');
                            btn.disabled = false;
                            btn.innerText = 'Update Annual Report';
                        });
                });
            @endif

            window.approveReport = function() {
                if (!confirm('Are you sure you want to formally approve this annual report? This will make it visible to the student.')) return;

                const btn = document.getElementById('approveBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Approving...';

                fetch("{{ route('student.results.annual.approve', [$student->id, $academicYear->id]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Approve Now';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('An error occurred. Please try again.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Approve Now';
                    });
            };
        });
    </script>
@endpush
