<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\AdmissionApplicationReceived;
use App\Mail\AdmissionStatusUpdated;

class AdmissionController extends Controller
{
    /**
     * Show the admission process page
     */
    public function process()
    {
        return view('admissions.process');
    }

    /**
     * Show the admission requirements page
     */
    public function requirements()
    {
        return view('admissions.requirements');
    }

    /**
     * Show the application form
     */
    public function application()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admissions.application', compact('branches'));
    }

    /**
     * Store a new admission application
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'branchId' => 'required|exists:branches,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'dateOfBirth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'nationality' => 'nullable|string|max:255',
            'stateOfOrigin' => 'nullable|string|max:255',
            'localGovernmentArea' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'churchDenomination' => 'nullable|string|max:255',
            'languageOfCommunication' => 'nullable|string|max:255',
            'numberOfChildrenInFamily' => 'nullable|integer|min:1|max:50',
            'positionInFamily' => 'nullable|integer|min:1|max:50',
            'schoolLastAttended' => 'nullable|string|max:255',
            'classLastAttended' => 'nullable|string|max:255',
            'hasHealthChallenges' => 'nullable|boolean',
            'healthChallengesDetails' => 'nullable|string|max:2000',
            'crisisResponse' => 'nullable|in:administer_first_aid,do_not_administer_first_aid,call_parents_guardians',
            'currentGrade' => 'nullable|string|max:50',
            'primaryContactName' => 'required|string|max:255',
            'fatherName' => 'nullable|string|max:255',
            'motherName' => 'nullable|string|max:255',
            'fatherResidentialAddress' => 'nullable|string|max:500',
            'motherResidentialAddress' => 'nullable|string|max:500',
            'fatherOccupation' => 'nullable|string|max:255',
            'motherOccupation' => 'nullable|string|max:255',
            'fatherOfficeAddress' => 'nullable|string|max:500',
            'motherOfficeAddress' => 'nullable|string|max:500',
            'fatherPhoneNumber' => 'nullable|string|max:20',
            'motherPhoneNumber' => 'nullable|string|max:20',
            'relationship' => 'required|string|max:100',
            'phoneNumber' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:1000',
            'hearAboutSchool' => 'nullable|string|max:100',
            'additionalInfo' => 'nullable|string|max:2000',
        ]);

        try {
            // Create the application
            $application = AdmissionApplication::create([
                'branch_id' => $validated['branchId'],
                'first_name' => $validated['firstName'],
                'last_name' => $validated['lastName'],
                'middle_name' => $validated['middleName'] ?? null,
                'date_of_birth' => $validated['dateOfBirth'],
                'gender' => $validated['gender'],
                'nationality' => $validated['nationality'] ?? null,
                'state_of_origin' => $validated['stateOfOrigin'] ?? null,
                'local_government_area' => $validated['localGovernmentArea'] ?? null,
                'religion' => $validated['religion'] ?? null,
                'church_denomination' => $validated['churchDenomination'] ?? null,
                'language_of_communication' => $validated['languageOfCommunication'] ?? null,
                'number_of_children_in_family' => $validated['numberOfChildrenInFamily'] ?? null,
                'position_in_family' => $validated['positionInFamily'] ?? null,
                'school_last_attended' => $validated['schoolLastAttended'] ?? null,
                'class_last_attended' => $validated['classLastAttended'] ?? null,
                'has_health_challenges' => (bool) ($validated['hasHealthChallenges'] ?? false),
                'health_challenges_details' => $validated['healthChallengesDetails'] ?? null,
                'crisis_response' => $validated['crisisResponse'] ?? null,
                'current_grade' => $validated['currentGrade'],
                'primary_contact_name' => $validated['primaryContactName'],
                'father_name' => $validated['fatherName'] ?? null,
                'mother_name' => $validated['motherName'] ?? null,
                'father_residential_address' => $validated['fatherResidentialAddress'] ?? null,
                'mother_residential_address' => $validated['motherResidentialAddress'] ?? null,
                'father_occupation' => $validated['fatherOccupation'] ?? null,
                'mother_occupation' => $validated['motherOccupation'] ?? null,
                'father_office_address' => $validated['fatherOfficeAddress'] ?? null,
                'mother_office_address' => $validated['motherOfficeAddress'] ?? null,
                'father_phone_number' => $validated['fatherPhoneNumber'] ?? null,
                'mother_phone_number' => $validated['motherPhoneNumber'] ?? null,
                'relationship' => $validated['relationship'],
                'phone_number' => $validated['phoneNumber'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'hear_about_school' => $validated['hearAboutSchool'],
                'additional_info' => $validated['additionalInfo'],
                'status' => 'pending',
            ]);

            // Send confirmation email to applicant
            try {
                Mail::to($validated['email'])->send(new AdmissionApplicationReceived($application));
            } catch (\Exception $e) {
                \Log::error('Failed to send admission confirmation email: ' . $e->getMessage());
            }
            
            // Send notification to admin staff
            try {
                $branch = Branch::find($validated['branchId']);
                if ($branch) {
                    // Get admin users for this branch
                    $adminUsers = \DB::table('branch_user')
                        ->where('branch_id', $validated['branchId'])
                        ->where('role', 'admin')
                        ->join('users', 'branch_user.user_id', '=', 'users.id')
                        ->select('users.*')
                        ->get();
                    
                    foreach ($adminUsers as $adminUser) {
                        Mail::to($adminUser->email)->send(new AdmissionApplicationReceived($application, true));
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin notification email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully! We will contact you within 2-3 business days.',
                'application_id' => $application->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error submitting your application. Please try again or contact us directly.'
            ], 500);
        }
    }

    /**
     * Admin: Show all admission applications
     */
    public function adminIndex(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Get filter parameters
        $status = $request->get('status');
        $branch = $request->get('branch');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Build query
        $query = AdmissionApplication::query();
        
        // Apply branch filter
        if (!$user->is_super_admin && $currentBranchId) {
            $query->where('branch_id', $currentBranchId);
        } elseif ($branch && $user->is_super_admin) {
            $query->where('branch_id', $branch);
        }
        
        // Apply status filter
        if ($status && in_array($status, ['pending', 'reviewed', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('primary_contact_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Apply date filters
        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }
        
        // Get applications with pagination
        $applications = $query->with('branch')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        // Get statistics
        if (!$user->is_super_admin && $currentBranchId) {
            $stats = [
                'total' => AdmissionApplication::where('branch_id', $currentBranchId)->count(),
                'pending' => AdmissionApplication::where('branch_id', $currentBranchId)->pending()->count(),
                'reviewed' => AdmissionApplication::where('branch_id', $currentBranchId)->reviewed()->count(),
                'approved' => AdmissionApplication::where('branch_id', $currentBranchId)->approved()->count(),
                'rejected' => AdmissionApplication::where('branch_id', $currentBranchId)->rejected()->count(),
            ];
        } else {
            $stats = [
                'total' => AdmissionApplication::count(),
                'pending' => AdmissionApplication::pending()->count(),
                'reviewed' => AdmissionApplication::reviewed()->count(),
                'approved' => AdmissionApplication::approved()->count(),
                'rejected' => AdmissionApplication::rejected()->count(),
            ];
        }
        
        // Get branches for filter (super admin only)
        $branches = null;
        if ($user->is_super_admin) {
            $branches = Branch::orderBy('name')->get();
        }

        return view('admin.admissions.index', compact('applications', 'stats', 'branches', 'status', 'branch', 'search', 'dateFrom', 'dateTo'));
    }

    /**
     * Admin: Show a specific application
     */
    public function adminShow(AdmissionApplication $application)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Branch admin can only view applications from their branch
        if (!$user->is_super_admin && $application->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        // Load the branch relationship
        $application->load('branch');
        
        return response()->json([
            'success' => true,
            'admission' => $application
        ]);
    }

    /**
     * Admin: Update application status
     */
    public function adminUpdateStatus(Request $request, AdmissionApplication $application)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Branch admin can only update applications from their branch
        if (!$user->is_super_admin && $application->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $application->status;
        $application->update($validated);

        // Send status update email to applicant
        try {
            Mail::to($application->email)->send(new AdmissionStatusUpdated($application, $oldStatus));
        } catch (\Exception $e) {
            \Log::error('Failed to send status update email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully'
        ]);
    }

    /**
     * Admin: Delete an application
     */
    public function adminDestroy(AdmissionApplication $application)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Branch admin can only delete applications from their branch
        if (!$user->is_super_admin && $application->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $application->delete();

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Application deleted successfully');
    }

    /**
     * Admin: Bulk update application statuses
     */
    public function bulkUpdateStatus(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:admission_applications,id',
            'status' => 'required|in:pending,reviewed,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $updatedCount = 0;
        $failedCount = 0;
        
        foreach ($validated['application_ids'] as $applicationId) {
            $application = AdmissionApplication::find($applicationId);
            
            // Check authorization
            if (!$user->is_super_admin && $application->branch_id != $currentBranchId) {
                $failedCount++;
                continue;
            }
            
            try {
                $oldStatus = $application->status;
                $application->update([
                    'status' => $validated['status'],
                    'admin_notes' => $validated['admin_notes'] ?: $application->admin_notes,
                ]);
                
                // Send status update email
                try {
                    Mail::to($application->email)->send(new AdmissionStatusUpdated($application, $oldStatus));
                } catch (\Exception $e) {
                    \Log::error('Failed to send bulk status update email: ' . $e->getMessage());
                }
                
                $updatedCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Failed to update application ' . $applicationId . ': ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Successfully updated {$updatedCount} applications. {$failedCount} failed.",
            'updated_count' => $updatedCount,
            'failed_count' => $failedCount,
        ]);
    }

    /**
     * Admin: Export applications to CSV
     */
    public function exportCsv(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Get filter parameters
        $status = $request->get('status');
        $branch = $request->get('branch');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Build query
        $query = AdmissionApplication::query();
        
        // Apply branch filter
        if (!$user->is_super_admin && $currentBranchId) {
            $query->where('branch_id', $currentBranchId);
        } elseif ($branch && $user->is_super_admin) {
            $query->where('branch_id', $branch);
        }
        
        // Apply status filter
        if ($status && in_array($status, ['pending', 'reviewed', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }
        
        // Apply date filters
        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }
        
        $applications = $query->with('branch')->get();
        
        $filename = 'admissions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'First Name', 'Last Name', 'Date of Birth', 'Age', 'Gender',
                'Current Grade', 'Primary Contact', 'Relationship', 'Phone', 'Email',
                'Address', 'Branch', 'Status', 'Admin Notes', 'How They Heard',
                'Additional Info', 'Submitted Date'
            ]);
            
            // CSV data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->first_name,
                    $application->last_name,
                    $application->date_of_birth->format('Y-m-d'),
                    $application->age,
                    $application->gender,
                    $application->current_grade ?: '',
                    $application->primary_contact_name,
                    $application->relationship,
                    $application->phone_number,
                    $application->email,
                    $application->address,
                    $application->branch->name ?? '',
                    $application->status,
                    $application->admin_notes ?: '',
                    $application->hear_about_school ?: '',
                    $application->additional_info ?: '',
                    $application->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
