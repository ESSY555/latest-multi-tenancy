<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Admission Application</title>
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
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
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .urgent {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
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
            background: #dc3545;
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
        <h1>🔔 New Admission Application</h1>
        <p>Action Required - Review Needed</p>
    </div>
    
    <div class="content">
        <p>Hello Admin,</p>
        
        <p>A new admission application has been submitted and requires your review.</p>
        
        <div class="highlight">
            <h3>Application Summary:</h3>
            <ul>
                <li><strong>Student Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</li>
                <li><strong>Date of Birth:</strong> {{ $application->date_of_birth->format('F j, Y') }} ({{ $application->age }} years old)</li>
                <li><strong>Gender:</strong> {{ ucfirst($application->gender) }}</li>
                <li><strong>Current Grade:</strong> {{ $application->current_grade ?: 'N/A' }}</li>
                <li><strong>Branch:</strong> {{ $application->branch->name }}</li>
                <li><strong>Application ID:</strong> #{{ $application->id }}</li>
                <li><strong>Submitted:</strong> {{ $application->created_at->format('F j, Y \a\t g:i A') }}</li>
            </ul>
        </div>
        
        <div class="highlight">
            <h3>Contact Information:</h3>
            <ul>
                <li><strong>Primary Contact:</strong> {{ $application->primary_contact_name }}</li>
                <li><strong>Relationship:</strong> {{ $application->relationship }}</li>
                <li><strong>Phone:</strong> {{ $application->phone_number }}</li>
                <li><strong>Email:</strong> {{ $application->email }}</li>
                <li><strong>Address:</strong> {{ $application->address }}</li>
            </ul>
        </div>
        
        @if($application->hear_about_school)
        <div class="highlight">
            <h3>Additional Information:</h3>
            <p><strong>How they heard about us:</strong> {{ $application->hear_about_school }}</p>
            @if($application->additional_info)
            <p><strong>Additional Notes:</strong> {{ $application->additional_info }}</p>
            @endif
        </div>
        @endif
        
        <div class="urgent">
            <h3>⚠️ Action Required:</h3>
            <p>Please review this application and update its status. You can:</p>
            <ul>
                <li>Mark as "Reviewed" if you need more time</li>
                <li>Approve if all requirements are met</li>
                <li>Reject if requirements are not met</li>
                <li>Add admin notes for internal reference</li>
            </ul>
        </div>
        
        <p><strong>Next Steps:</strong></p>
        <ol>
            <li>Review the application details</li>
            <li>Check if all required documents are provided</li>
            <li>Verify eligibility criteria</li>
            <li>Update application status</li>
            <li>Contact applicant if additional information is needed</li>
        </ol>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/admissions') }}" class="button">Review Application</a>
        </div>
        
        <p>This application is currently marked as <strong>PENDING</strong> and will remain so until you take action.</p>
        
        <p>Best regards,<br>
        <strong>Admissions System</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification. Please log into the admin panel to take action.</p>
        <p>&copy; {{ date('Y') }} {{ $application->branch->name }}. All rights reserved.</p>
    </div>
</body>
</html>

