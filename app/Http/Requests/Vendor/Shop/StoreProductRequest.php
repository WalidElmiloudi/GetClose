<?php

namespace App\Http\Requests\Vendor\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'vendor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'price' => 'required|float|min:0.1',
            'quantity' => 'required|integer|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'image|meme:jpeg,png,webp,jpg|max:2048',
            'category_id' => 'required|integer'
        ];
    }
}
