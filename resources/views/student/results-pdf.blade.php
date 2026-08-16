                                <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Report Card - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #1a202c;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .border-box {
            border: 1px solid #1f2937;
            padding: 15px;
            position: relative;
        }

        .header-table {
            width: 100%;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .school-name {
            font-size: 26px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            color: #2563eb;
            letter-spacing: -0.5px;
        }

        .school-branch {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2563eb;
            margin-top: 2px;
        }

        .school-motto {
            font-size: 13px;
            font-style: italic;
            font-weight: bold;
            color: #dc2626;
            margin: 4px 0;
        }

        .school-info {
            font-size: 11px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }

        .student-info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
            color: #000;
            width: 130px;
            text-transform: uppercase;
        }

        .info-value {
            border-bottom: 1px solid #4b5563;
            text-transform: uppercase;
            font-weight: bold;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
        }

        .results-table th {
            background-color: #f3f4f6;
            border: 1px solid #1f2937;
            padding: 3px;
            font-style: italic;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }

        .results-table td {
            border: 1px solid #1f2937;
            padding: 3px;
            text-align: center;
            font-weight: bold;
            color: #0826d8;
            text-transform: uppercase;
        }

        .domain-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            font-weight: bold;
            color: #0826d8;
            text-transform: uppercase;
        }

        .domain-table th {
            background-color: #f3f4f6;
            border: 1px solid #000;
            padding: 4px;
            font-style: italic;
            color: #0826d8;
        }

        .domain-table td {
            border: 1px solid #000;
            padding: 4px;
            color: #0826d8;
            font-weight: bold;
        }

        .score-summary {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            font-weight: bold;
            color: #0826d8;
            text-transform: uppercase;
        }

        .score-summary td {
            border: 1px solid #000;
            padding: 4px;
        }

        .score-summary th {
            background-color: #f3f4f6;
            border: 1px solid #000;
            padding: 4px;
            font-style: italic;
            color: #0826d8;
        }

        .comment-section {
            width: 100%;
            margin-top: 20px;
            font-size: 11px;
        }

        .comment-label {
            font-weight: 900;
            text-transform: uppercase;
            color: #0826d8;
            font-style: italic;
            width: 160px;
        }

        .comment-value {
            border-bottom: 1px solid #4b5563;
            margin-left: 165px;
            font-weight: 500;
            font-style: italic;
            text-transform: uppercase;
            color: #0826d8;
        }

        .signature-box {
            border: 1px solid #000;
            height: 40px;
            width: 120px;
            background-color: #f9fafb;
            margin-top: 5px;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #6b7280;
            font-style: italic;
            border-top: 1px solid #d1d5db;
            padding-top: 5px;
        }

        .text-red {
            color: #dc2626 !important;
        }

        .text-blue {
            color: #0826d8 !important;
        }

        .text-black {
            color: #000 !important;
        }

        .font-black {
            font-weight: 900;
        }
    </style>
    </head>
    
    <body>
        <div class="container">
            <div class="border-box">
                <!-- Header -->
                <table class="header-table">
                    <tr>
                        <td width="80">
                            <img src="{{ public_path('images/school logo.jpeg') }}" width="70" height="70">
                        </td>
                        <td align="center">
                            <div class="school-name">BEZALEEL INTERNATIONAL SCHOOL</div>
                            <div class="school-branch">MPAPE ABUJA</div>
                            <div class="school-motto">Motto: Towards Excellence</div>
                            <div class="school-info">TELEPHONE: <span class="text-black">07014907969, 08052123760</span>
                            </div>
                        </td>
                        <td width="80" align="right">
                            @php
                                $rawPhoto = $student->profile_photo ?? $student->studentProfile?->profile_photo;
                            @endphp
                            @if ($rawPhoto)
                                <img src="{{ str_starts_with($rawPhoto, 'http') ? $rawPhoto : public_path('uploads/profile-photos/' . basename($rawPhoto)) }}" alt="passport"
                                    style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #000;">
                            @else
                                <div style="width: 70px; height: 70px; border: 1px solid #000; font-size: 8px; text-align: center; color: #9ca3af; text-transform: uppercase; font-style: italic; padding-top: 25px;">
                                    STUDENT<br>PASSPORT
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
    
                <!-- Student Info -->
                <table class="student-info-table">
                    <tr>
                        <td width="50%">
                            <table>
                                <tr>
                                    <td class="info-label">SESSION:</td>
                                    <td class="info-value">{{ $term->academicYear->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">STUDENT NAME:</td>
                                    <td class="info-value"><strong>{{ $student->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="info-label">CLASS:</td>
                                    <td class="info-value">{{ $results->first()?->schoolClass->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">ADMISSION NO:</td>
                                    <td class="info-value">{{ $student->studentProfile->admission_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%">
                            <table>
                                <tr>
                                    <td class="info-label">TERM:</td>
                                    <td class="info-value"><strong>{{ $term->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="info-label">NEXT TERM BEGINS:</td>
                                    <td class="info-value">{{ $results->first()?->next_term_begins ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">AVERAGE SCORE:</td>
                                    <td class="info-value"><strong>{{ number_format($averageScore, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="info-label">NEXT TERM FEES:</td>
                                    <td class="info-value">
                                        {{ $results->first()?->next_term_fees ?? '—' }}
                                        <span class="text-red"
                                            style="font-size: 8px; text-transform: none; display: block; margin-top: 2px;">(To
                                            be paid during holiday /Resumption day)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
    
                <!-- Academic Table -->
                <table class="results-table">
                    <thead>
                        <tr>
                            <th width="20">S/N</th>
                            <th align="left">SUBJECTS</th>
                            <th width="35">CA1(10)</th>
                            <th width="35">CA2(10)</th>
                            <th width="35">CA3(10)</th>
                            <th width="40">EXAM(60)</th>
                            <th width="40">TOTAL</th>
                            <th width="40">CLASS AVG</th>
                            <th width="40">HIGHEST IN CLASS</th>
                            <th width="40">LOWEST IN CLASS</th>
                            <th width="35">GRADE</th>
                            <th width="50">REMARK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $index => $result)
                            <tr>
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td align="left">{{ $result->subject->name }}</td>
                                <td>{{ number_format($result->ca1, 0) }}</td>
                                <td>{{ number_format($result->ca2, 0) }}</td>
                                <td>{{ number_format($result->ca3, 0) }}</td>
                                <td>{{ number_format($result->exam, 0) }}</td>
                                <td class="{{ $result->total < 45 ? 'text-red' : '' }}">
                                    {{ number_format($result->total, 0) }}
                                </td>
                                <td>{{ number_format((float) ($result->class_average ?? 0), 2) }}</td>
                                <td>{{ number_format($result->class_highest ?? 0, 0) }}</td>
                                <td>{{ number_format($result->class_lowest ?? 0, 0) }}</td>
                                <td>{{ $result->grade }}</td>
                                <td style="font-size: 9px;">{{ $result->remark }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    
                <!-- Domains & Summary -->
                <table width="100%">
                    <tr>
                        <td width="32%" valign="top">
                            <table class="domain-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Psychomotor Domain</th>
                                    </tr>
                                </thead>
                                @php
                                    $psyTraits = ['Handwriting', 'Fluency', 'Participation in Sports', 'Handling Tools (Practical)', 'Music/Performance Skill'];
                                    $psychomotorData = $results->first()->psychomotor ?? [];
                                @endphp
                                @foreach($psyTraits as $trait)
                                    <tr>
                                        <td width="70%">{{ $trait }}</td>
                                        <td align="center" class="font-black">{{ $psychomotorData[$trait] ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td width="2%"></td>
                        <td width="32%" valign="top">
                            <table class="domain-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Affective Domain</th>
                                    </tr>
                                </thead>
                                @php
                                    $affTraits = ['General Conduct', 'Spiritual Development', 'Neatness/Cleanliness', 'Punctuality', 'Reliability'];
                                    $affectiveData = $results->first()->affective ?? [];
                                @endphp
                                @foreach($affTraits as $trait)
                                    <tr>
                                        <td width="70%">{{ $trait }}</td>
                                        <td align="center" class="font-black">{{ $affectiveData[$trait] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td width="2%"></td>
                        <td width="32%" valign="top">
                            <table class="score-summary">
                                <thead>
                                    <tr>
                                        <th colspan="2"
                                            style="background-color: #f7fafc; border: 1px solid #000; padding: 4px; text-transform: uppercase;">
                                            Result Summary</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td width="60%">Total mark obtainable</td>
                                    <td align="center" class="font-black text-black">
                                        {{ number_format(($totalSubjects ?? count($results)) * 100, 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total mark obtained</td>
                                    <td align="center" class="font-black text-black">{{ number_format($totalScore, 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Average</td>
                                    <td align="center" class="font-black text-black">{{ number_format($averageScore, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"
                                        style="font-size: 8px; background-color: #f7fafc; height: 12px; font-weight: bold;">
                                        GRADES INTERPRETATION</td>
                                </tr>
                                 @if(($classType ?? 'default') === 'ss')
                                     <tr>
                                         <td>80 – 100 (EXCELLENT)</td>
                                         <td align="center">A1</td>
                                     </tr>
                                     <tr>
                                         <td>75 – 79 (VERY GOOD)</td>
                                         <td align="center">B2</td>
                                     </tr>
                                     <tr>
                                         <td>70 – 74 (GOOD)</td>
                                         <td align="center">B3</td>
                                     </tr>
                                     <tr>
                                         <td>65 – 69 (CREDIT)</td>
                                         <td align="center">C4</td>
                                     </tr>
                                     <tr>
                                         <td>60 – 64 (CREDIT)</td>
                                         <td align="center">C5</td>
                                     </tr>
                                     <tr>
                                         <td>55 – 59 (CREDIT)</td>
                                         <td align="center">C6</td>
                                     </tr>
                                     <tr>
                                         <td>50 – 54 (PASS)</td>
                                         <td align="center">D7</td>
                                     </tr>
                                     <tr>
                                         <td>45 – 49 (PASS)</td>
                                         <td align="center">E8</td>
                                     </tr>
                                     <tr>
                                         <td>0 – 44 (FAIL)</td>
                                         <td align="center" class="text-red">F9</td>
                                     </tr>
                                 @elseif(($classType ?? 'default') === 'js')
                                     <tr>
                                         <td>80 – 100 (EXCELLENT)</td>
                                         <td align="center">A1</td>
                                     </tr>
                                     <tr>
                                         <td>55 – 79 (CREDIT)</td>
                                         <td align="center">C</td>
                                     </tr>
                                     <tr>
                                         <td>45 – 54 (PASS)</td>
                                         <td align="center">P</td>
                                     </tr>
                                     <tr>
                                         <td>0 – 44 (FAIL)</td>
                                         <td align="center" class="text-red">F</td>
                                     </tr>
                                 @else
                                     <tr>
                                         <td>100 - 70 (EXCELLENT)</td>
                                         <td align="center">A</td>
                                     </tr>
                                     <tr>
                                         <td>69 - 60 (VERY GOOD)</td>
                                         <td align="center">B</td>
                                     </tr>
                                     <tr>
                                         <td>59 - 50 (GOOD)</td>
                                         <td align="center">C</td>
                                     </tr>
                                     <tr>
                                         <td>49 - 40 (PASS)</td>
                                         <td align="center">D</td>
                                     </tr>
                                     <tr>
                                         <td>39 - 0 (FAIL)</td>
                                         <td align="center" class="text-red">F</td>
                                     </tr>
                                 @endif
                            </table>
                        </td>
                    </tr>
                </table>
    
                <!-- Comments & Signatures -->
                <table class="comment-section">
                    <tr>
                        <td width="70%">
                            <div style="margin-bottom: 10px;">
                                <span class="comment-label">Form Teacher:</span>
                                <div class="comment-value">
                                    {{ $results->first()?->form_teacher_comment ?: 'Assessment Pending...' }}
                                </div>
                            </div>
                            <div>
                                <span class="comment-label">Principal's Remark:</span>
                                <div class="comment-value">
                                {{ $results->first()?->school_head_comment ?: 'Remark Pending...' }}
                            </div>
                        </div>
                    </td>
                    <td width="30%" align="right">
                        <div style="text-align: center; display: inline-block; margin-right: 10px;">
                            <div
                                style="font-size: 8px; font-weight: bold; text-transform: uppercase; color: #718096; margin-bottom: 2px;">
                                Form Teacher</div>
                            <div class="signature-box">
                                @if($results->first() && $results->first()->form_teacher_signature)
                                    <img src="{{ public_path('uploads/' . $results->first()->form_teacher_signature) }}"
                                        height="40">
                                @endif
                            </div>
                        </div>
                        <div style="text-align: center; display: inline-block;">
                            <div
                                style="font-size: 8px; font-weight: bold; text-transform: uppercase; color: #718096; margin-bottom: 2px;">
                                Principal</div>
                            <div class="signature-box">
                                @if($term && $term->principal_signature)
                                    <img src="{{ public_path('uploads/' . $term->principal_signature) }}"
                                        height="40">
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="footer">
                <table width="100%">
                    <tr>
                        <td align="left">Authorized report card generated via Bezaleel School Portal</td>
                        <td align="right">Date Verified: {{ now()->format('d M Y (H:i)') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
