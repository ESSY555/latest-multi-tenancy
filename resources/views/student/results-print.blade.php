<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Result - {{ $student->name }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #111827;
            background: #f8fafc;
        }

        .page {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 18px;
            background: #ffffff;
            color: #111827;
        }

        .print-controls {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 14px;
        }

        .print-controls button {
            background: #1f2937;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 9999px;
            cursor: pointer;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .header {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
        }

        .logo img,
        .photo img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
        }

        .logo {
            width: 100px;
            min-width: 100px;
        }

        .photo {
            width: 100px;
            min-width: 100px;
            text-align: center;
        }

        .photo-placeholder {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
            padding: 10px;
            line-height: 1.2;
        }

        .school-title {
            text-align: center;
        }

        .school-title h1,
        .school-title h2 {
            margin: 0;
            line-height: 1.05;
        }

        .school-title h1 {
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #1d4ed8;
        }

        .school-title h2 {
            font-size: 18px;
            color: #1d4ed8;
            font-weight: 700;
            margin-top: 4px;
        }

        .school-title p {
            margin: 6px 0 0;
            font-size: 10px;
            color: #dc2626;
            font-style: italic;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .info-grid div {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 6px;
            font-size: 10px;
        }

        .info-label {
            font-weight: 700;
            text-transform: uppercase;
            color: #111827;
            min-width: 120px;
        }

        .info-value {
            border-bottom: 1px solid #111827;
            font-weight: 700;
            padding-bottom: 1px;
            text-transform: uppercase;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            border-spacing: 0;
            margin: 0;
        }

        th,
        td {
            border: 1px solid #111827;
            padding: 6px 7px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #e2e8f0;
            color: #1d4ed8;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
        }

        td {
            font-weight: 600;
            color: #1d4ed8;
        }

        td.subject {
            text-align: left;
            padding-left: 10px;
            color: #111827;
        }

        .text-small {
            font-size: 9px;
        }

        .domain-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .domain-table th,
        .domain-table td,
        .summary-table th,
        .summary-table td {
            font-size: 9px;
            padding: 5px 6px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #1d4ed8;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        .signature-box {
            border: 1px solid #111827;
            min-height: 52px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        .signature-label {
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
            color: #1d4ed8;
        }

        .footer {
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #4b5563;
            text-transform: uppercase;
        }

        .watermark {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.06;
            font-size: 120px;
            letter-spacing: 12px;
            color: #1d4ed8;
            white-space: nowrap;
            pointer-events: none;
        }

        .page-inner {
            position: relative;
        }

        @media print {
            body {
                font-size: 10px;
                color: #111827;
                background: #ffffff;
            }

            .print-controls {
                display: none;
            }

            .page {
                box-shadow: none;
                margin: 0;
                padding: 4mm;
                width: auto;
                background: transparent;
            }

            .header,
            .info-grid,
            .table-wrapper,
            .domain-grid,
            .signature-grid,
            .footer {
                page-break-inside: avoid;
            }

            table, tr, td, th {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            @page {
                size: A4 portrait;
                margin: 6mm;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="print-controls no-print">
            <button type="button" onclick="window.print()">Print Result</button>
        </div>

        <div class="page-inner">
            <div class="watermark">RESULT</div>
            <div class="header">
                <div class="logo">
                    <img src="{{ asset('images/bezalee-logo-main.PNG') }}" alt="School Logo">
                </div>
                <div class="school-title">
                    <h1>Bezaleel International School</h1>
                    <h2>Mpape Abuja</h2>
                    <p>Motto: Towards Excellence</p>
                    <p class="text-small">Telephone: 07014907969, 08052123760</p>
                </div>
                <div class="photo">
                    @php
                        $rawPhoto = $student->profile_photo ?? $student->studentProfile?->profile_photo;
                    @endphp
                    @if ($rawPhoto)
                        <img src="{{ str_starts_with($rawPhoto, 'http') ? $rawPhoto : asset('uploads/profile-photos/' . basename($rawPhoto)) }}" alt="Student Photo">
                    @else
                        <div class="photo-placeholder">Student Passport</div>
                    @endif
                </div>
            </div>

            <div class="info-grid">
                <div>
                    <div class="info-label">Session:</div>
                    <div class="info-value">{{ $term->academicYear->name ?? 'N/A' }}</div>
                    <div class="info-label">Student Name:</div>
                    <div class="info-value">{{ $student->name }}</div>
                    <div class="info-label">Class:</div>
                    <div class="info-value">{{ $results->first()?->schoolClass->name ?? 'N/A' }}</div>
                    <div class="info-label">Admission No:</div>
                    <div class="info-value">{{ $student->studentProfile->admission_number ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="info-label">Term:</div>
                    <div class="info-value">{{ $term->name ?? 'N/A' }}</div>
                    <div class="info-label">Next Term Begins:</div>
                    <div class="info-value">{{ $results->first()?->next_term_begins ?? '—' }}</div>
                    <div class="info-label">Average Score:</div>
                    <div class="info-value">{{ number_format($averageScore, 2) }}</div>
                    <div class="info-label">Next Term Fees:</div>
                    <div class="info-value">{{ $results->first()?->next_term_fees ?? '—' }}</div>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th align="left">Subject</th>
                            <th>CA1</th>
                            <th>CA2</th>
                            <th>CA3</th>
                            <th>Exam</th>
                            <th>Total</th>
                            <th>Class Avg</th>
                            <th>Highest</th>
                            <th>Lowest</th>
                            <th>Grade</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $index => $result)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="subject">{{ $result->subject->name }}</td>
                                <td>{{ number_format($result->ca1, 0) }}</td>
                                <td>{{ number_format($result->ca2, 0) }}</td>
                                <td>{{ number_format($result->ca3, 0) }}</td>
                                <td>{{ number_format($result->exam, 0) }}</td>
                                <td class="{{ $result->total < 45 ? 'text-red' : '' }}">{{ number_format($result->total, 0) }}</td>
                                <td>{{ number_format((float) ($result->class_average ?? 0), 2) }}</td>
                                <td>{{ number_format($result->class_highest ?? 0, 0) }}</td>
                                <td>{{ number_format($result->class_lowest ?? 0, 0) }}</td>
                                <td>{{ $result->grade ?? '-' }}</td>
                                <td class="text-small">{{ $result->remark ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="domain-grid">
                <div>
                    <div class="section-title">Psychomotor Domain</div>
                    <table class="domain-table">
                        <tbody>
                            @php
                                $psyTraits = ['Handwriting', 'Fluency', 'Participation in Sports', 'Handling Tools (Practical)', 'Music/Performance Skill'];
                                $psychomotorData = $results->first()->psychomotor ?? [];
                            @endphp
                            @foreach($psyTraits as $trait)
                                <tr>
                                    <td style="text-align:left;">{{ $trait }}</td>
                                    <td>{{ $psychomotorData[$trait] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="section-title">Affective Domain</div>
                    <table class="domain-table">
                        <tbody>
                            @php
                                $affTraits = ['General Conduct', 'Spiritual Development', 'Neatness/Cleanliness', 'Punctuality', 'Reliability'];
                                $affectiveData = $results->first()->affective ?? [];
                            @endphp
                            @foreach($affTraits as $trait)
                                <tr>
                                    <td style="text-align:left;">{{ $trait }}</td>
                                    <td>{{ $affectiveData[$trait] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="section-title">Result Summary</div>
                    <table class="summary-table">
                        <tbody>
                            <tr>
                                <td>Total mark obtainable</td>
                                <td>{{ number_format(($totalSubjects ?? 0) * 100, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Total mark obtained</td>
                                <td>{{ number_format($totalScore, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Average</td>
                                <td>{{ number_format($averageScore, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-weight:700; text-align:center;">Grades Interpretation</td>
                            </tr>
                            @if(($classType ?? 'default') === 'ss')
                                <tr><td>80 – 100 (EXCELLENT)</td><td>A1</td></tr>
                                <tr><td>75 – 79 (VERY GOOD)</td><td>B2</td></tr>
                                <tr><td>70 – 74 (GOOD)</td><td>B3</td></tr>
                                <tr><td>65 – 69 (CREDIT)</td><td>C4</td></tr>
                                <tr><td>60 – 64 (CREDIT)</td><td>C5</td></tr>
                                <tr><td>55 – 59 (CREDIT)</td><td>C6</td></tr>
                                <tr><td>50 – 54 (PASS)</td><td>D7</td></tr>
                                <tr><td>45 – 49 (PASS)</td><td>E8</td></tr>
                                <tr><td>0 – 44 (FAIL)</td><td>F9</td></tr>
                            @elseif(($classType ?? 'default') === 'js')
                                <tr><td>80 – 100 (EXCELLENT)</td><td>A1</td></tr>
                                <tr><td>55 – 79 (CREDIT)</td><td>C</td></tr>
                                <tr><td>45 – 54 (PASS)</td><td>P</td></tr>
                                <tr><td>0 – 44 (FAIL)</td><td>F</td></tr>
                            @else
                                <tr><td>100 - 70 (EXCELLENT)</td><td>A</td></tr>
                                <tr><td>69 - 60 (VERY GOOD)</td><td>B</td></tr>
                                <tr><td>59 - 50 (GOOD)</td><td>C</td></tr>
                                <tr><td>49 - 40 (PASS)</td><td>D</td></tr>
                                <tr><td>39 - 0 (FAIL)</td><td>F</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="signature-grid">
                <div>
                    <div class="signature-label">Form Teacher Comment</div>
                    <div class="signature-box" style="text-align:left; font-size: 9px; line-height: 1.2; color: #111827;">
                        {{ $results->first()?->form_teacher_comment ?? 'Comment pending...' }}
                    </div>
                </div>
                <div>
                    <div class="signature-label">Principal's Remark</div>
                    <div class="signature-box" style="text-align:left; font-size: 9px; line-height: 1.2; color: #111827;">
                        {{ $results->first()?->school_head_comment ?? 'Remark pending...' }}
                    </div>
                </div>
            </div>

            <div class="signature-grid">
                <div>
                    <div class="signature-label">Form Teacher Signature</div>
                    <div class="signature-box">
                        @if($results->first()?->form_teacher_signature)
                            <img src="{{ asset('storage/' . $results->first()->form_teacher_signature) }}" alt="Form Teacher Signature" style="max-height: 52px; max-width: 100%; object-fit: contain;">
                        @else
                            <span style="font-size: 9px; color: #6b7280;">Signature area</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="signature-label">Principal Signature</div>
                    <div class="signature-box">
                        @if($term && $term->principal_signature)
                            <img src="{{ asset('storage/' . $term->principal_signature) }}" alt="Principal Signature" style="max-height: 52px; max-width: 100%; object-fit: contain;">
                        @else
                            <span style="font-size: 9px; color: #6b7280;">Signature area</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="footer">
                <div>Result generated via Bezaleel School Portal</div>
                <div>Date: {{ now()->format('d M Y (H:i)') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
