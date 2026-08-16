<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Application Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .highlight {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #2196f3;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Admission Application Received</h1>
        <p>Thank you for applying to our school!</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $application->primary_contact_name }}</strong>,</p>
        
        <p>We have successfully received your admission application for <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>.</p>
        
        <div class="highlight">
            <h3>Application Details:</h3>
            <ul>
                <li><strong>Student Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</li>
                <li><strong>Date of Birth:</strong> {{ $application->date_of_birth->format('F j, Y') }}</li>
                <li><strong>Current Grade:</strong> {{ $application->current_grade ?: 'N/A' }}</li>
                <li><strong>School Branch:</strong> {{ $application->branch->name }}</li>
                <li><strong>Application ID:</strong> #{{ $application->id }}</li>
            </ul>
        </div>
        
        <p>Our admissions team will review your application within <strong>2-3 business days</strong>. You will receive an email notification once your application has been reviewed.</p>
        
        <p><strong>What happens next?</strong></p>
        <ol>
            <li>Our team will review your application</li>
            <li>We may contact you for additional information if needed</li>
            <li>You'll receive a status update via email</li>
            <li>If approved, we'll guide you through the enrollment process</li>
        </ol>
        
        <p>If you have any questions or need to provide additional information, please don't hesitate to contact us:</p>
        
        <div class="highlight">
            <p><strong>Contact Information:</strong></p>
            <p>📧 Email: support@bezaleelsch.com</p>
            <p>📞 Phone: +234 8052123760</p>
            <p>📍 Address: {{ $application->branch->address ?? 'Contact us for address details' }}</p>
        </div>
        
        <p>Thank you for choosing our school. We look forward to the possibility of welcoming {{ $application->first_name }} to our community!</p>
        
        <p>Best regards,<br>
        <strong>Admissions Team</strong><br>
        {{ $application->branch->name }}</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ $application->branch->name }}. All rights reserved.</p>
    </div>
</body>
</html>

