<?php

namespace App\Http\Requests;

use App\Support\InputSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class AnalyticsEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'session_id' => InputSanitizer::text($this->input('session_id'), 64),
            'event_type' => InputSanitizer::text($this->input('event_type'), 32),
            'path' => $this->input('path') ? InputSanitizer::path((string) $this->input('path')) : null,
            'section' => InputSanitizer::text($this->input('section'), 64),
            'referrer' => InputSanitizer::text($this->input('referrer'), 500),
            'user_agent' => InputSanitizer::text($this->input('user_agent'), 500),
            'website' => $this->input('website'),
        ]);
    }

    public function rules(): array
    {
        return [
            'session_id' => ['required', 'string', 'max:64', 'regex:/^[a-zA-Z0-9\-_]+$/'],
            'event_type' => ['required', 'string', 'in:page_view,section_view,section_click'],
            'path' => ['required', 'string', 'max:255', 'regex:/^\/[a-zA-Z0-9\-_\/]*$/'],
            'section' => ['nullable', 'string', 'max:64', 'regex:/^[a-zA-Z0-9\-_]*$/'],
            'referrer' => ['nullable', 'string', 'max:500'],
            'user_agent' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'size:0'],
        ];
    }
}
