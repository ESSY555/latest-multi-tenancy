<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Branch;
use App\Models\SchoolClass;
use App\Mail\ParentEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BulkEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super.admin');
    }

    /**
     * Show the bulk email form
     */
    public function index()
    {
        // Get all branches for super admin selection
        $branches = Branch::orderBy('name')->get();
        
        // Get current branch (for default selection)
        $currentBranchId = session('current_branch_id');
        
        // Get all classes in current branch for targeting
        $classes = SchoolClass::where('branch_id', $currentBranchId)
            ->withCount(['enrollments' => function($query) {
                $query->whereHas('student.studentProfile');
            }])
            ->orderBy('name')
            ->get();

        // Get recipient statistics for current branch (student account emails)
        $parentStats = $this->getParentEmailStats($currentBranchId);
        
        // Get statistics for all branches
        $allBranchesStats = $this->getAllBranchesStats();

        return view('admin.bulk-email.index', compact('classes', 'parentStats', 'branches', 'allBranchesStats'));
    }

    /**
     * Send bulk email to parent-facing student account emails
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'branch_selection' => 'required|in:current,all,specific',
            'branch_ids' => 'required_if:branch_selection,specific|array',
            'branch_ids.*' => 'exists:branches,id',
            'target_type' => 'required|in:all,class,specific',
            'class_ids' => 'required_if:target_type,class|array',
            'class_ids.*' => 'exists:school_classes,id',
            'specific_emails' => 'required_if:target_type,specific|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $parentEmails = $this->getParentEmails($request);

        if (empty($parentEmails)) {
            return redirect()->back()
                ->with('error', 'No parent emails found for the selected criteria.')
                ->withInput();
        }

        // Send emails
        $sentCount = 0;
        $failedCount = 0;
        $failedEmails = [];

        foreach ($parentEmails as $emailData) {
            try {
                Mail::to($emailData['email'])->send(
                    new ParentEmail(
                        $request->subject,
                        $request->message,
                        $emailData['parent_name'],
                        $emailData['student_name']
                    )
                );
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $failedEmails[] = $emailData['email'];
            }
        }

        $message = "Bulk email sent successfully! Sent to {$sentCount} recipients.";
        if ($failedCount > 0) {
            $message .= " Failed to send to {$failedCount} emails: " . implode(', ', $failedEmails);
        }

        return redirect()->route('bulk-email.index')
            ->with('success', $message);
    }

    /**
     * Get recipient email statistics for a specific branch
     */
    private function getParentEmailStats($branchId)
    {
        $totalParents = StudentProfile::whereHas('user', function($query) {
            $query->whereNotNull('email')
                ->where('email', '!=', '')
                ->where('email', 'like', '%@%');
        })->whereHas('user.enrollments.schoolClass', function($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->count();

        $parentsWithValidEmails = StudentProfile::whereHas('user', function($query) {
            $query->whereNotNull('email')
                ->where('email', '!=', '')
                ->where('email', 'like', '%@%');
        })->whereHas('user.enrollments.schoolClass', function($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->count();

        return [
            'total' => $totalParents,
            'valid_emails' => $parentsWithValidEmails,
            'invalid_emails' => $totalParents - $parentsWithValidEmails
        ];
    }

    /**
     * Get recipient email statistics for all branches
     */
    private function getAllBranchesStats()
    {
        $totalParents = StudentProfile::whereHas('user', function($query) {
            $query->whereNotNull('email')
                ->where('email', '!=', '')
                ->where('email', 'like', '%@%');
        })->whereHas('user.enrollments.schoolClass')
            ->count();

        $parentsWithValidEmails = StudentProfile::whereHas('user', function($query) {
            $query->whereNotNull('email')
                ->where('email', '!=', '')
                ->where('email', 'like', '%@%');
        })->whereHas('user.enrollments.schoolClass')
            ->count();

        return [
            'total' => $totalParents,
            'valid_emails' => $parentsWithValidEmails,
            'invalid_emails' => $totalParents - $parentsWithValidEmails
        ];
    }

    /**
     * Get recipient emails based on targeting criteria
     */
    private function getParentEmails(Request $request)
    {
        // Determine which branches to include
        $branchIds = $this->getTargetBranchIds($request);
        
        $query = StudentProfile::whereHas('user', function($query) {
            $query->whereNotNull('email')
                ->where('email', '!=', '')
                ->where('email', 'like', '%@%');
        })->whereHas('user.enrollments.schoolClass', function($query) use ($branchIds) {
            $query->whereIn('branch_id', $branchIds);
        })->with(['user.enrollments.schoolClass.branch']);

        switch ($request->target_type) {
            case 'class':
                if ($request->has('class_ids')) {
                    $query->whereHas('user.enrollments', function($query) use ($request) {
                        $query->whereIn('school_class_id', $request->class_ids);
                    });
                }
                break;
            
            case 'specific':
                if ($request->specific_emails) {
                    $emails = array_map('trim', explode(',', $request->specific_emails));
                    $query->whereHas('user', function($query) use ($emails) {
                        $query->whereIn('email', $emails);
                    });
                }
                break;
            
            case 'all':
            default:
                // No additional filtering needed
                break;
        }

        $profiles = $query->get();

        return $profiles->map(function($profile) {
            $studentName = $profile->user->name;
            $enrollment = $profile->user->enrollments->first();
            $className = $enrollment?->schoolClass?->name ?? 'Unknown Class';
            $branchName = $enrollment?->schoolClass?->branch?->name ?? 'Unknown Branch';
            
            return [
                'email' => $profile->user->email,
                'parent_name' => $profile->parent_name,
                'student_name' => $studentName,
                'class_name' => $className,
                'branch_name' => $branchName
            ];
        })->unique('email')->values()->toArray();
    }

    /**
     * Get target branch IDs based on selection
     */
    private function getTargetBranchIds(Request $request)
    {
        switch ($request->branch_selection) {
            case 'all':
                return Branch::pluck('id')->toArray();
            
            case 'specific':
                return $request->branch_ids ?? [];
            
            case 'current':
            default:
                return [session('current_branch_id')];
        }
    }

    /**
     * Preview email before sending
     */
    public function preview(Request $request)
    {
        // Debug: Log all request data
        \Log::info('Preview Request Data:', $request->all());
        
        // Simple validation for testing
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'branch_selection' => 'required|in:current,all,specific',
            'target_type' => 'required|in:all,class,specific',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ], 400);
        }

        // For now, return a simple test response
        return response()->json([
            'recipient_count' => 5,
            'recipients' => [
                [
                    'email' => 'test@example.com',
                    'parent_name' => 'Test Parent',
                    'student_name' => 'Test Student',
                    'class_name' => 'Test Class',
                    'branch_name' => 'Test Branch'
                ]
            ],
            'preview_subject' => $request->subject,
            'preview_message' => $request->message
        ]);
    }
}
