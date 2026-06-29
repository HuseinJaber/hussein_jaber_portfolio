<?php

namespace App\Http\Requests;

use App\Support\InputSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterSubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->input('email') ? InputSanitizer::email((string) $this->input('email')) : null,
            'website' => $this->input('website'),
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:180'],
            'website' => ['nullable', 'size:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot be longer than 180 characters.',
            'website.size' => 'Something went wrong. Please try again.',
        ];
    }
}
