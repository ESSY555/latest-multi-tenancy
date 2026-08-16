<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);
            min-height: 100vh;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #7c3aed 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        .header-content {
            position: relative;
            z-index: 1;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header .subtitle {
            margin: 8px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #374151;
            font-weight: 500;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 35px;
            color: #4b5563;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid #3b82f6;
        }
        .student-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #93c5fd;
            padding: 20px;
            margin: 25px 0;
            border-radius: 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .student-info p {
            margin: 0;
            font-size: 15px;
            color: #1e40af;
            font-weight: 500;
        }
        .student-info .icon {
            display: inline-block;
            margin-right: 8px;
            color: #3b82f6;
        }
        .footer {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 8px 0;
            font-size: 14px;
            color: #64748b;
        }
        .footer .signature {
            font-weight: 600;
            color: #374151;
            font-size: 16px;
        }
        .footer .school-name {
            color: #3b82f6;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
            margin: 20px 0;
        }
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 8px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 30px 20px;
            }
            .footer {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="header-content">
                <h1>📧 School Communication</h1>
                <p class="subtitle">Important Message from School Administration</p>
            </div>
        </div>
        
        <div class="content">
            <div class="greeting">
                @if($parentName)
                    Dear {{ $parentName }},
                @else
                    Dear Parent/Guardian,
                @endif
            </div>
            
            @if($studentName)
                <div class="student-info">
                    <p>
                        <span class="icon">👨‍🎓</span>
                        <strong>Student:</strong> {{ $studentName }}
                        <span class="badge">Active</span>
                    </p>
                </div>
            @endif
            
            <div class="message">
                {!! nl2br(e($message)) !!}
            </div>
            
            <div class="divider"></div>
        </div>
        
        <div class="footer">
            <p class="signature">Best regards,</p>
            <p class="school-name">School Administration Team</p>
            <div class="divider"></div>
            <p>📱 This is an automated message from our school management system</p>
            <p>⚠️ Please do not reply directly to this email</p>
            <p>📞 For urgent matters, please contact the school directly</p>
        </div>
    </div>
</body>
</html>

