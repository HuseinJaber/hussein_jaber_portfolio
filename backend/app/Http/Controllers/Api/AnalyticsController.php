<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnalyticsEventRequest;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Response;

class AnalyticsController extends Controller
{
    public function store(AnalyticsEventRequest $request): Response
    {
        $validated = $request->validated();

        AnalyticsEvent::create([
            'session_id' => $validated['session_id'],
            'event_type' => $validated['event_type'],
            'path' => $validated['path'],
            'section' => $validated['section'] ?? null,
            'referrer' => $validated['referrer'] ?? null,
            'user_agent' => $validated['user_agent'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        return response()->noContent();
    }
}
