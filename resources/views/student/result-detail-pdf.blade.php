<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $result->subject->name ?? 'N/A' }} Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0070C0;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #0070C0;
            font-size: 28px;
        }
        .subject-title {
            font-size: 22px;
            color: #333;
            margin-top: 10px;
        }
        .student-info {
            margin-bottom: 20px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .student-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #0070C0;
        }
        .score-cards {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .score-card {
            flex: 1;
            min-width: 120px;
            background: white;
            border: 2px solid #0070C0;
            border-radius: 8px;
            padding: 20px;
            margin: 0 10px 10px 0;
            text-align: center;
        }
        .score-card-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .score-card-value {
            font-size: 32px;
            font-weight: bold;
            color: #0070C0;
        }
        .total-section {
            background: #0070C0;
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
        }
        .total-label {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .total-value {
            font-size: 48px;
            font-weight: bold;
        }
        .grade-section {
            background: white;
            border: 3px solid #0070C0;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
        }
        .grade-label {
            font-size: 18px;
            color: #0070C0;
            margin-bottom: 10px;
        }
        .grade-value {
            font-size: 48px;
            font-weight: bold;
        }
        .grade-A { color: #27ae60; }
        .grade-B { color: #2980b9; }
        .grade-C { color: #f39c12; }
        .grade-D { color: #e67e22; }
        .grade-F { color: #c0392b; }
        .grade-scale {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .grade-scale h3 {
            color: #0070C0;
            margin-top: 0;
        }
        .grade-range {
            display: inline-block;
            margin: 10px 15px;
            text-align: center;
        }
        .grade-range-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .grade-range-scale {
            font-size: 12px;
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Result</h1>
        <p class="subject-title">{{ $result->subject->name ?? 'N/A' }}</p>
        <p>Generated on {{ date('F j, Y') }}</p>
    </div>

    <div class="student-info">
        <p><span class="info-label">Student Name:</span> {{ $user->name }}</p>
        <p><span class="info-label">Admission Number:</span> {{ $student->admission_number }}</p>
    </div>

    <!-- Score Cards -->
    <div class="score-cards">
        <div class="score-card">
            <div class="score-card-label">CAT 1</div>
            <div class="score-card-value">{{ $result->ca1 }}</div>
        </div>
        <div class="score-card">
            <div class="score-card-label">NPW</div>
            <div class="score-card-value">{{ $result->ca2 }}</div>
        </div>
        <div class="score-card">
            <div class="score-card-label">CAT 2</div>
            <div class="score-card-value">{{ $result->ca3 }}</div>
        </div>
        <div class="score-card">
            <div class="score-card-label">EXAM</div>
            <div class="score-card-value">{{ $result->exam }}</div>
        </div>
    </div>

    <!-- Total Section -->
    <div class="total-section">
        <div class="total-label">TOTAL SCORE</div>
        <div class="total-value">{{ $result->total }}</div>
    </div>

    <!-- Grade Section -->
    <div class="grade-section">
        <div class="grade-label">Grade</div>
        <div class="grade-value grade-{{ $result->grade }}">{{ $result->grade }}</div>
    </div>

    <!-- Grade Scale -->
    <div class="grade-scale">
        <h3>Grade Scale Reference</h3>
        <div class="grade-range">
            <div class="grade-range-value grade-A">A</div>
            <div class="grade-range-scale">80 - 100</div>
        </div>
        <div class="grade-range">
            <div class="grade-range-value grade-B">B</div>
            <div class="grade-range-scale">70 - 79</div>
        </div>
        <div class="grade-range">
            <div class="grade-range-value grade-C">C</div>
            <div class="grade-range-scale">60 - 69</div>
        </div>
        <div class="grade-range">
            <div class="grade-range-value grade-D">D</div>
            <div class="grade-range-scale">50 - 59</div>
        </div>
        <div class="grade-range">
            <div class="grade-range-value grade-F">F</div>
            <div class="grade-range-scale">Below 50</div>
        </div>
    </div>

    <div class="footer">
        <p>This is an official academic report. Please keep it safe for your records.</p>
    </div>
</body>
</html>

