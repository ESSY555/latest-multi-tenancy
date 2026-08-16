<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white p-6 text-[12px] text-gray-900">
    <div class="max-w-[1100px] mx-auto border border-gray-800 p-5">
        <div class="mb-5">
            <div class="relative text-[#25306f] min-h-[120px]">
                <div class="absolute left-0 top-0 w-[86px] h-[98px]">
                    <img src="{{ asset('/images/bezalee-logo-main.PNG') }}" alt="school logo" class="w-full h-full object-contain">
                </div>

                <div class="mx-[92px] text-center leading-tight">
                    <h1 class="text-[23px] font-black tracking-[0.8px] uppercase">BEZALEEL INTERNATIONAL COLLEGE</h1>
                    <h2 class="text-[15px] font-black tracking-[1.2px] uppercase -mt-0.5">MPAPE ABUJA</h2>
                    <p class="text-[11px] italic font-semibold mt-0.5">MOTTO: Towards Excellence</p>
                    <p class="text-[11px] font-bold mt-0.5">TELEPHONE: 07011731515 &nbsp; 08052123760</p>
                    <p class="text-[27px] leading-none font-black text-red-600 underline underline-offset-2 mt-1.5">MOCK</p>
                </div>

                <div class="absolute right-0 top-[54px] w-[305px] leading-[1.06] text-left">
                    <p class="text-[14px] font-black uppercase tracking-[0.7px]">CONTINUOUS ASSESSMENT DOSSIER</p>
                    <p class="text-[14px] font-black uppercase tracking-[0.7px]">FOR SENIOR SECONDARY SCHOOL</p>
                </div>
            </div>

            <div class="mt-2 space-y-1.5 text-[12px] font-semibold uppercase">
                <div class="flex items-end gap-1">
                    <span class="whitespace-nowrap">Name:</span><span class="flex-1 border-b border-gray-700 h-4"></span>
                    <span class="whitespace-nowrap">Admission Number:</span><span class="w-[130px] border-b border-gray-700 h-4"></span>
                    <span class="whitespace-nowrap">Term:</span><span class="w-[62px] border-b border-gray-700 h-4"></span>
                    <span class="whitespace-nowrap">Class:</span><span class="w-[78px] border-b border-gray-700 h-4"></span>
                </div>
                <div class="flex items-end gap-1">
                    <span class="whitespace-nowrap">No. of times school opened:</span><span class="w-[85px] border-b border-gray-700 h-4"></span>
                    <span class="whitespace-nowrap">No. of attendance:</span><span class="w-[95px] border-b border-gray-700 h-4"></span>
                    <span class="whitespace-nowrap">Total average score:</span><span class="w-[90px] border-b border-gray-700 h-4"></span>
                    <span class="whitespace-nowrap">Next term begins:</span><span class="flex-1 border-b border-gray-700 h-4"></span>
                </div>
            </div>
        </div>

        <table class="w-full border-collapse border border-gray-800 mt-4 text-[11px]">
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
                </tr>
            </thead>
            <tbody>
                @php
$subjects = [
    'MATHEMATICS',
    'ENGLISH STUDIES',
    'AGRICULTURAL SCIENCE',
    'CIVIC EDUCATION',
    'PHYSICS',
    'DATA PROCESSING',
    'CHEMISTRY',
    'CRS',
    'GEOGRAPHY',
    'BIOLOGY',
    'FINANCIAL ACCOUNTING',
    'HISTORY',
    'COMMERCE',
    'GOVERNMENT',
    'ECONOMICS',
    'MORAL EDUCATION',
    'MARKETING',
    'AFFECTIVE & PSYCHOMOTOR'
];
                @endphp

                @foreach($subjects as $index => $subject)
                    <tr>
                        <td class="border border-gray-800 p-1 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-800 p-1 text-left pl-2">{{ $subject }}</td>
                        <td class="border border-gray-800 p-1"></td>
                        <td class="border border-gray-800 p-1"></td>
                        <td class="border border-gray-800 p-1"></td>
                        <td class="border border-gray-800 p-1"></td>
                        <td class="border border-gray-800 p-1"></td>
                        <td class="border border-gray-800 p-1"></td>
                        <td class="border border-gray-800 p-1"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>




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
                        <tr><td class="border border-black p-1 font-semibold">Handwriting</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Fluency</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Sports</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Neatness</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Music Skills</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-bold">Total</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
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
                        <tr><td class="border border-black p-1 font-semibold">Honesty</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Spiritual Development</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Neatness</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Punctuality</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-semibold">Reliability</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                        <tr><td class="border border-black p-1 font-bold">Total</td><td class="border border-black"></td><td class="border border-black"></td><td class="border border-black"></td></tr>
                    </tbody>
                </table>
            </div>

            {{-- Result Summary --}}
            <div class="w-full">
                <table class="w-full border border-black border-collapse table-fixed text-[10px] uppercase">
                    <tbody>
                        <tr>
                            <td class="border border-black p-1.5 font-semibold w-[62%]">Total Mark Obtainable</td>
                            <td class="border border-black p-1.5"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1.5 font-semibold">Total Mark Obtained</td>
                            <td class="border border-black p-1.5"></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-1.5 font-semibold">Average</td>
                            <td class="border border-black p-1.5"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black p-1.5 text-center font-bold">Grades Interpretation</td>
                        </tr>
                        <tr><td class="border border-black p-1">84-100 (PASS) D7</td><td class="border border-black p-1 text-center">45-50 (PASS) E8</td></tr>
                        <tr><td class="border border-black p-1">74-100 (Excellent) A1</td><td class="border border-black p-1 text-center">0-44 (Fail) F9</td></tr>
                        <tr><td class="border border-black p-1">69-75 (Very Good) B2</td><td class="border border-black p-1 text-center"></td></tr>
                        <tr><td class="border border-black p-1">60-74 (Good) B3</td><td class="border border-black p-1 text-center"></td></tr>
                        <tr><td class="border border-black p-1">55-60 (Credit) C4</td><td class="border border-black p-1 text-center"></td></tr>
                        <tr><td class="border border-black p-1">50-55 (Credit) C5</td><td class="border border-black p-1 text-center"></td></tr>
                        <tr><td class="border border-black p-1">45-50 (Credit) C6</td><td class="border border-black p-1 text-center"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>




        <div class="mt-7 space-y-8 text-[12px]">
            <!-- Comment Lines -->
            <div class="flex items-end gap-2">
                <span class="font-bold uppercase whitespace-nowrap">Class Teacher's Remark</span>
                <span class="flex-1 border-b border-gray-700 h-5"></span>
            </div>

            <div class="flex items-end gap-2">
                <span class="font-bold uppercase whitespace-nowrap">Principal</span>
                <span class="flex-1 border-b border-gray-700 h-5"></span>
            </div>

            <!-- Signature Lines -->
            <div class="flex justify-between items-start pt-3">
                <div class="w-[300px]">
                    <div class="w-[78%] border-b border-dotted border-gray-600 h-5"></div>
                    <p class="font-black uppercase tracking-wide text-[#25306f] mt-1">Class Teacher's Signature</p>
                </div>
                <div class="w-[300px]">
                    <div class="w-[78%] border-b border-dotted border-gray-600 h-5"></div>
                    <p class="font-black uppercase tracking-wide text-[#25306f] mt-1">Principal's Signature &amp; Stamp</p>
                </div>
            </div>
        </div>




    </div>

</body>

</html>
