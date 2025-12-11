<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust with policies if needed
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'file', 'image', 'max:2048'], // max 2MB
        ];
    }
}
