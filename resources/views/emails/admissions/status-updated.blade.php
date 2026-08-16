<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Status Updated</title>
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
        .status-approved {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }
        .status-rejected {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
        .status-reviewed {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
        <h1>📋 Admission Status Update</h1>
        <p>Your application has been reviewed</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $application->primary_contact_name }}</strong>,</p>
        
        <p>Your admission application for <strong>{{ $application->first_name }} {{ $application->last_name }}</strong> has been reviewed and the status has been updated.</p>
        
        <div class="highlight">
            <h3>Application Details:</h3>
            <ul>
                <li><strong>Student Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</li>
                <li><strong>Application ID:</strong> #{{ $application->id }}</li>
                <li><strong>School Branch:</strong> {{ $application->branch->name }}</li>
                <li><strong>Previous Status:</strong> {{ ucfirst($oldStatus) }}</li>
                <li><strong>New Status:</strong> {{ ucfirst($application->status) }}</li>
            </ul>
        </div>
        
        @if($application->status === 'approved')
        <div class="status-approved">
            <h3>🎉 Congratulations! Your application has been APPROVED!</h3>
            <p>We are pleased to inform you that {{ $application->first_name }} has been accepted to our school. Here's what happens next:</p>
            <ol>
                <li>You will receive enrollment documents within 3-5 business days</li>
                <li>Complete and return all required forms</li>
                <li>Submit any required documentation (birth certificate, immunization records, etc.)</li>
                <li>Pay the enrollment fee</li>
                <li>Attend orientation (date to be announced)</li>
            </ol>
        </div>
        @elseif($application->status === 'rejected')
        <div class="status-rejected">
            <h3>Application Status: REJECTED</h3>
            <p>We regret to inform you that we are unable to offer admission at this time. This decision may be due to:</p>
            <ul>
                <li>Class capacity limitations</li>
                <li>Missing required documentation</li>
                <li>Eligibility criteria not met</li>
                <li>Other administrative factors</li>
            </ul>
            <p>If you believe this decision was made in error or if you would like to discuss your options, please contact our admissions office.</p>
        </div>
        @elseif($application->status === 'reviewed')
        <div class="status-reviewed">
            <h3>Application Status: REVIEWED</h3>
            <p>Your application has been reviewed by our admissions team. We may need additional information or documentation to proceed. Please check your email for specific requirements or contact us directly.</p>
        </div>
        @endif
        
        @if($application->admin_notes)
        <div class="highlight">
            <h3>Administrative Notes:</h3>
            <p>{{ $application->admin_notes }}</p>
        </div>
        @endif
        
        <p><strong>Next Steps:</strong></p>
        @if($application->status === 'approved')
        <p>Our enrollment team will contact you within 3-5 business days with detailed instructions and required documents.</p>
        @elseif($application->status === 'rejected')
        <p>If you have questions about this decision or would like to discuss alternatives, please contact us.</p>
        @else
        <p>Please wait for further communication from our admissions team regarding next steps.</p>
        @endif
        
        <p>If you have any questions, please don't hesitate to contact us:</p>
        
        <div class="highlight">
            <p><strong>Contact Information:</strong></p>
            <p>📧 Email: support@bezaleelsch.com</p>
            <p>📞 Phone: +234 8052123760</p>
            <p>📍 Address: {{ $application->branch->address ?? 'Contact us for address details' }}</p>
        </div>
        
        <p>Thank you for your interest in our school.</p>
        
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

