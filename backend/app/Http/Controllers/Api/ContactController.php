<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Models\ContactMessage;
use App\Services\PortfolioNotifier;

class ContactController extends Controller
{
    public function store(ContactMessageRequest $request)
    {
        $validated = $request->validated();

        $message = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        PortfolioNotifier::contactSubmitted($message);

        return response()->json([
            'message' => "Thanks for reaching out! I've sent a confirmation to your inbox and will get back to you shortly.",
            'id' => $message->id,
        ], 201);
    }
}
