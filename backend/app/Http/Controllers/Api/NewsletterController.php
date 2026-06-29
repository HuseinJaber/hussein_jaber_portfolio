<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use App\Services\PortfolioNotifier;

class NewsletterController extends Controller
{
    public function store(NewsletterSubscribeRequest $request)
    {
        $validated = $request->validated();

        $email = $validated['email'];
        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing?->is_active) {
            return response()->json([
                'message' => "You're already subscribed — thank you for staying in touch!",
            ]);
        }

        $subscriber = $existing ?? new NewsletterSubscriber(['email' => $email]);

        $subscriber->fill([
            'email' => $email,
            'ip_address' => $request->ip(),
            'is_active' => true,
        ])->save();

        PortfolioNotifier::newsletterSubscribed($subscriber);

        return response()->json([
            'message' => 'Welcome aboard! Check your inbox for a confirmation email.',
        ], 201);
    }
}
