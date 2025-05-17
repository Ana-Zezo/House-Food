<?php

namespace App\Http\Requests\Chef;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class FoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            $lang = App::getLocale();
            $message = $lang === 'ar' ? 'خطأ في التحقق' : 'Validation Error';

            $response = response()->json([
                'status' => false,
                'errors' => $validator->errors()->all(),
            ], 422);

            throw new HttpResponseException($response);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image',
            'price' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0|lt:price',
            'status' => 'nullable|in:active,inactive',
            'preparation_time' => 'required|integer|min:1',
        ];
    }
}