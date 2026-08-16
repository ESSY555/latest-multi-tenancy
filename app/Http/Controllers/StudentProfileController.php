<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class StudentProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'branch.selected']);
    }

    /**
     * Show the student profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is a student in current branch
        $this->authorizeStudent();
        
        return view('dashboard.student.profile', compact('user'));
    }

    /**
     * Update profile photo only
     */
    public function updateProfilePhoto(Request $request)
    {
        abort(403, 'Updating profile is disabled for students.');
        $user = Auth::user();
        $this->authorizeStudent();

        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ], [
            'profile_photo.max' => 'The profile photo should not be greater than 5MB.',
        ]);

        // Delete old profile photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
        }

        // Store new profile photo
        $file = $request->file('profile_photo');
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('profile-photos', $file, $filename);
        $path = $filename;
        $user->update(['profile_photo' => $path]);

        return back()->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Update emergency contact information
     */
    public function updateEmergencyContact(Request $request)
    {
        abort(403, 'Updating emergency contact is disabled for students.');
        $user = Auth::user();
        $this->authorizeStudent();

        $request->validate([
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
        ]);

        // Update emergency contact information
        $user->update([
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
        ]);

        return back()->with('success', 'Emergency contact updated successfully!');
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        abort(403, 'Updating profile is disabled for students.');
        $user = Auth::user();
        $this->authorizeStudent();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        // Update user information
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'bio' => $request->bio,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        abort(403, 'Updating password is disabled for students.');
        $user = Auth::user();
        $this->authorizeStudent();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $this->authorizeStudent();

        $request->validate([
            'email_notifications' => ['boolean'],
            'assignment_reminders' => ['boolean'],
            'grade_notifications' => ['boolean'],
            'announcement_notifications' => ['boolean'],
            'attendance_alerts' => ['boolean'],
        ]);

        $user->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'assignment_reminders' => $request->boolean('assignment_reminders'),
            'grade_notifications' => $request->boolean('grade_notifications'),
            'announcement_notifications' => $request->boolean('announcement_notifications'),
            'attendance_alerts' => $request->boolean('attendance_alerts'),
        ]);

        return back()->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Delete profile photo
     */
    public function deleteProfilePhoto()
    {
        abort(403, 'Deleting profile photo is disabled for students.');
        $user = Auth::user();
        $this->authorizeStudent();

        if ($user->profile_photo) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            $user->update(['profile_photo' => null]);
        }

        return back()->with('success', 'Profile photo deleted successfully!');
    }

    /**
     * Authorize that user is a student in current branch
     */
    private function authorizeStudent()
    {
        $user = Auth::user();
        $currentBranchId = session('current_branch_id');
        
        $studentRole = \DB::table('branch_user')
            ->where('user_id', $user->id)
            ->where('branch_id', $currentBranchId)
            ->where('role', 'student')
            ->first();
        
        if (!$studentRole) {
            abort(403, 'You do not have student access in this branch.');
        }
    }
}
