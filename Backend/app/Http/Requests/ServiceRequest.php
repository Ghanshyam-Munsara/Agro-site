<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: Add admin authentication check when implementing auth
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $serviceId = $this->route('service') ? $this->route('service')->id : null;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'active_clients' => ['nullable', 'integer', 'min:0'],
            'status' => [
                'nullable',
                Rule::in(['active', 'inactive', 'pending'])
            ],
        ];

        // Service ID validation with format check (S001, S002, etc.)
        if ($isUpdate && $serviceId) {
            $rules['service_id'] = [
                'nullable',
                'string',
                'max:20',
                'regex:/^S\d{3,}$/',
                Rule::unique('services', 'service_id')->ignore($serviceId)
            ];
        } else {
            $rules['service_id'] = [
                'nullable',
                'string',
                'max:20',
                'regex:/^S\d{3,}$/',
                'unique:services,service_id'
            ];
        }

        // Price validation: if price is provided, price_type should be provided too
        if ($this->has('price') && $this->input('price') !== null) {
            $rules['price'] = ['required', 'numeric', 'min:0', 'max:999999.99'];
            $rules['price_type'] = [
                'required',
                Rule::in(['fixed', 'monthly', 'hourly', 'per_unit'])
            ];
        } else {
            $rules['price'] = ['nullable', 'numeric', 'min:0', 'max:999999.99'];
            $rules['price_type'] = [
                'nullable',
                Rule::in(['fixed', 'monthly', 'hourly', 'per_unit'])
            ];
        }

        // Image validation: accept either file upload or URL string
        if ($this->hasFile('image')) {
            // File upload validation
            $rules['image'] = [
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:5120', // 5MB max
                'dimensions:max_width=2000,max_height=2000'
            ];
        } else {
            // URL string validation
            $rules['image_url'] = [
                'nullable',
                'string',
                'max:500',
                'url'
            ];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Set default status if not provided and creating new service
        if (!$this->has('status') && $this->isMethod('POST')) {
            $this->merge(['status' => 'active']);
        }

        // If service_id is provided, ensure it matches format
        if ($this->has('service_id') && $this->input('service_id')) {
            $serviceId = strtoupper(trim($this->input('service_id')));
            // Ensure it starts with S and has numbers
            if (!preg_match('/^S\d+$/', $serviceId)) {
                // Auto-format: if it's just numbers, add S prefix
                if (preg_match('/^\d+$/', $serviceId)) {
                    $serviceId = 'S' . str_pad($serviceId, 3, '0', STR_PAD_LEFT);
                } else {
                    // Try to extract numbers and format
                    preg_match('/\d+/', $serviceId, $matches);
                    if (!empty($matches)) {
                        $serviceId = 'S' . str_pad($matches[0], 3, '0', STR_PAD_LEFT);
                    }
                }
                $this->merge(['service_id' => $serviceId]);
            }
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Service name is required.',
            'name.max' => 'Service name cannot exceed 255 characters.',
            'description.max' => 'Service description cannot exceed 5000 characters.',
            'category.required' => 'Service category is required.',
            'category.max' => 'Service category cannot exceed 100 characters.',
            'service_id.required' => 'Service ID is required.',
            'service_id.unique' => 'Service ID already exists. Please use a different ID.',
            'service_id.regex' => 'Service ID must be in format S001, S002, etc. (S followed by numbers).',
            'service_id.max' => 'Service ID cannot exceed 20 characters.',
            'icon.max' => 'Icon name cannot exceed 50 characters.',
            'price.required' => 'Price is required when price type is specified.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'price_type.required' => 'Price type is required when price is specified.',
            'price_type.in' => 'Invalid price type. Must be one of: fixed, monthly, hourly, per_unit.',
            'active_clients.integer' => 'Active clients must be a whole number.',
            'active_clients.min' => 'Active clients cannot be negative.',
            'status.in' => 'Invalid service status. Must be one of: active, inactive, pending.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, jpg, png, gif, webp.',
            'image.max' => 'Image size must not exceed 5MB.',
            'image.dimensions' => 'Image dimensions must not exceed 2000x2000 pixels.',
            'image_url.url' => 'Image URL must be a valid URL.',
            'image_url.max' => 'Image URL cannot exceed 500 characters.',
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
            'name' => 'service name',
            'description' => 'service description',
            'category' => 'service category',
            'service_id' => 'service ID',
            'icon' => 'icon',
            'price' => 'price',
            'price_type' => 'price type',
            'active_clients' => 'active clients',
            'status' => 'service status',
            'image' => 'service image',
            'image_url' => 'image URL',
        ];
    }
}
