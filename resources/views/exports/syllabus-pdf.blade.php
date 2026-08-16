<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Syllabus Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .branch-info {
            font-size: 14px;
            color: #666;
        }
        .date-info {
            text-align: right;
            margin-bottom: 20px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #333;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">SCHOOL MANAGEMENT SYSTEM</div>
        <div class="report-title">SYLLABUS REPORT</div>
        @if($branch)
            <div class="branch-info">{{ $branch->name }}</div>
        @endif
    </div>

    <div class="date-info">
        Generated on: {{ date('F d, Y \a\t g:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Class</th>
                <th>Subject</th>
                <th>Term</th>
                <th>Topics</th>
                <th>Duration</th>
                <th>Learning Objectives</th>
            </tr>
        </thead>
        <tbody>
            @forelse($syllabi as $syllabus)
                <tr>
                    <td>{{ $syllabus->class }}</td>
                    <td>{{ $syllabus->subject }}</td>
                    <td>{{ $syllabus->term }}</td>
                    <td>{{ Str::limit($syllabus->topics, 100) }}</td>
                    <td>{{ $syllabus->duration }}</td>
                    <td>{{ Str::limit($syllabus->objectives ?? 'N/A', 80) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No syllabus entries found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Total Subjects:</strong> {{ $syllabi->count() }}</p>
        <p><strong>Classes Covered:</strong> {{ $syllabi->unique('class')->count() }}</p>
        <p><strong>Terms:</strong> {{ $syllabi->unique('term')->count() }}</p>
        @if($syllabi->count() > 0)
            <p><strong>Last Updated:</strong> {{ $syllabi->sortByDesc('updated_at')->first()->updated_at->format('M d, Y') }}</p>
        @endif
    </div>

    <div class="footer">
        <p>This report was generated automatically by the School Management System</p>
        <p>© {{ date('Y') }} All rights reserved</p>
    </div>
</body>
</html>

