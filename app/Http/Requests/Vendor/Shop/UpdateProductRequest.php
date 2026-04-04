<?php

namespace App\Http\Requests\Vendor\Shop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quantity' => 'sometimes|integer|min:0',
            'price' => 'sometimes|float|min:0.1',
            'status' => 'sometimes|string'
        ];
    }
}
