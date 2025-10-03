<?php

namespace App\Domain\Messaging\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:5000'],
            'attachments' => ['sometimes', 'array'],
            'attachments.*' => ['file', 'max:20480'], // 20MB per file
        ];
    }

    /**
     * Custom messages for validation errors (optional)
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Message content cannot be empty.',
            'body.max' => 'Message cannot exceed 5000 characters.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each attachment cannot exceed 20MB.',
        ];
    }
}
