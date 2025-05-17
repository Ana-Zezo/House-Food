<?php

namespace App\Http\Requests\Chef;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFoodRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|integer|exists:categories,id',
            'chef_id' => 'sometimes|integer|exists:chefs,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'preparation_time' => 'sometimes|integer|min:0',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'food_type' => 'sometimes|in:full,half,raw',
            'image' => 'sometimes|image',
            'status' => 'sometimes|in:active,inactive',
        ];
    }
}