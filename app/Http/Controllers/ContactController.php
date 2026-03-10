<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('mainpage.contact-us');
    }

    public function send(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string'
        ]);

        $contact = ContactMessage::create([
            'role' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message
        ]);

        /*
        |---------------------------------------------------------
        | 1️⃣ SEND MESSAGE TO ADMIN
        |---------------------------------------------------------
        */

        Mail::raw(
            "New Contact Message\n\n" .
            "Role: {$contact->role}\n" .
            "Name: {$contact->name}\n" .
            "Email: {$contact->email}\n" .
            "Phone: {$contact->phone}\n\n" .
            "Message:\n{$contact->message}",
            function ($mail) {
                $mail->to('yanogyanog20@gmail.com')
                    ->subject('New Contact Message - JobAbroad');
            }
        );

        /*
        |---------------------------------------------------------
        | 2️⃣ AUTO REPLY TO USER (DYNAMIC EMAIL)
        |---------------------------------------------------------
        */

        Mail::raw(
            "Hello {$contact->name},\n\n" .
            "Thank you for contacting JobAbroad.\n\n" .
            "We have received your message and our team will get back to you shortly.\n\n" .
            "Your Message:\n{$contact->message}\n\n" .
            "Best regards,\nJobAbroad Support Team",
            function ($mail) use ($contact) {
                $mail->to($contact->email)
                    ->subject('We Received Your Message - JobAbroad');
            }
        );

        if ($request->filled('website')) {
            return back();
        }

        return back()->with('success', 'Message sent successfully!');
    }
}