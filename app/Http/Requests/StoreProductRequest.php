<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', 'unique:products,product_name'],
            'description' => ['required', 'string', 'max:1000'],
            'category_id' => ['required', 'exists:categories,category_id'],
            'unit_price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'lowstock_alert' => ['required', 'integer', 'min:0', 'max:999999'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'The product name field is required.',
            'product_name.string' => 'The product name must be a valid text.',
            'product_name.max' => 'The product name may not be greater than 255 characters.',
            'product_name.unique' => 'A product with this name already exists.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a valid text.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'category_id.required' => 'The category field is required.',
            'category_id.exists' => 'The selected category is invalid.',
            'unit_price.required' => 'The unit price field is required.',
            'unit_price.numeric' => 'The unit price must be a valid number.',
            'unit_price.min' => 'The unit price must be at least 0.',
            'unit_price.max' => 'The unit price may not be greater than 999,999.99.',
            'lowstock_alert.required' => 'The low stock alert level field is required.',
            'lowstock_alert.integer' => 'The low stock alert level must be a whole number.',
            'lowstock_alert.min' => 'The low stock alert level must be at least 0.',
            'lowstock_alert.max' => 'The low stock alert level may not be greater than 999,999.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, jpg, png, gif, svg.',
            'image.max' => 'The image may not be greater than 2MB.',
        ];
    }
}

