<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\'\.]+$/' // Allow letters, spaces, hyphens, apostrophes, dots
            ],
            'email' => [
                'required',
                'email:rfc,dns', // RFC compliant email with DNS check
                'max:255'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/' // International phone format
            ],
            'subject' => [
                'required',
                Rule::in([
                    Contact::SUBJECT_GENERAL,
                    Contact::SUBJECT_SERVICE,
                    Contact::SUBJECT_CONSULTATION,
                    Contact::SUBJECT_SUPPORT,
                    Contact::SUBJECT_PARTNERSHIP,
                    Contact::SUBJECT_OTHER,
                ])
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:2000'
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Trim whitespace from inputs
        $this->merge([
            'name' => trim($this->input('name', '')),
            'email' => strtolower(trim($this->input('email', ''))),
            'phone' => $this->input('phone') ? trim($this->input('phone')) : null,
            'message' => trim($this->input('message', '')),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, apostrophes, and dots.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.regex' => 'Please provide a valid phone number format (e.g., +1234567890, (123) 456-7890).',
            'subject.required' => 'Subject is required.',
            'subject.in' => 'Invalid subject selected. Please choose from the available options.',
            'message.required' => 'Message is required.',
            'message.min' => 'Message must be at least 10 characters long.',
            'message.max' => 'Message cannot exceed 2000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'name',
            'email' => 'email address',
            'phone' => 'phone number',
            'subject' => 'subject',
            'message' => 'message',
        ];
    }
}
