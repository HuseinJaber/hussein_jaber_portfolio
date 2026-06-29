<?php

namespace App\Http\Requests;

use App\Support\InputSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => InputSanitizer::text($this->input('name'), 120),
            'email' => $this->input('email') ? InputSanitizer::email((string) $this->input('email')) : null,
            'subject' => InputSanitizer::text($this->input('subject'), 180),
            'message' => InputSanitizer::text($this->input('message'), 5000),
            'website' => $this->input('website'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['nullable', 'size:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.max' => 'Name cannot be longer than 120 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot be longer than 180 characters.',
            'subject.max' => 'Subject cannot be longer than 180 characters.',
            'message.required' => 'Please enter a message.',
            'message.max' => 'Message cannot be longer than 5000 characters.',
            'website.size' => 'Something went wrong. Please try again.',
        ];
    }
}
