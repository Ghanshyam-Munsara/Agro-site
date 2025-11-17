<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product') ? $this->route('product')->id : null;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => [
                'required',
                Rule::in(['seeds', 'fertilizers', 'equipment', 'tools'])
            ],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'currency' => ['nullable', 'string', 'size:3'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'status' => [
                'nullable',
                Rule::in(['active', 'inactive', 'out_of_stock'])
            ],
        ];

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
        // Set default currency if not provided
        if (!$this->has('currency')) {
            $this->merge(['currency' => 'USD']);
        }

        // Set default status if not provided and creating new product
        if (!$this->has('status') && $this->isMethod('POST')) {
            $this->merge(['status' => 'active']);
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
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'description.max' => 'Product description cannot exceed 5000 characters.',
            'category.required' => 'Product category is required.',
            'category.in' => 'Invalid product category. Must be one of: seeds, fertilizers, equipment, tools.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be greater than 0.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'currency.size' => 'Currency code must be exactly 3 characters (e.g., USD, EUR).',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, jpg, png, gif, webp.',
            'image.max' => 'Image size must not exceed 5MB.',
            'image.dimensions' => 'Image dimensions must not exceed 2000x2000 pixels.',
            'image_url.url' => 'Image URL must be a valid URL.',
            'image_url.max' => 'Image URL cannot exceed 500 characters.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'status.in' => 'Invalid product status. Must be one of: active, inactive, out_of_stock.',
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
            'name' => 'product name',
            'description' => 'product description',
            'category' => 'product category',
            'price' => 'product price',
            'currency' => 'currency code',
            'image' => 'product image',
            'image_url' => 'image URL',
            'stock_quantity' => 'stock quantity',
            'status' => 'product status',
        ];
    }
}
