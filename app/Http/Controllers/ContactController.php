<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Log the contact form submission
            Log::info('Contact form submission received:', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // In a real application, you would send an email here
            // Mail::to('support@k-glow.com')->send(new ContactFormMail($request->all()));

            return redirect()->route('contact-us')->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            return redirect()->route('contact-us')->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly.');
        }
    }
}



