<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Mail::raw($validated['message'], function($message) use ($validated) {
            $message->from($validated['email'], $validated['name'])
                   ->to(config('mail.contact_email'))
                   ->subject($validated['subject']);
        });

        return back()->with('success', 'Teie sõnum on saadetud. Võtame teiega peagi ühendust!');
    }
} 