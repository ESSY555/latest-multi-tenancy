<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        $branches = Branch::all();
        
        return view('contact', compact('branches'));
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'branch_id' => 'required|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'branch_id' => $request->branch_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // TODO: Send notification email to admin
            // Mail::to('admin@school.com')->send(new ContactFormSubmitted($contact));

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! We will get back to you soon.',
                'contact_id' => $contact->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display contact form with specific branch pre-selected
     */
    public function showBranch($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $branches = Branch::all();
        
        return view('contact', compact('branches', 'branch'));
    }
}
