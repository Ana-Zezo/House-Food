<?php

namespace App\Http\Requests\User;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegisterRequest extends FormRequest
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
                'message' => $message,
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
            'name' => 'required|string|min:3',
            'email' => 'nullable|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'phone' => [
                'required',
                'digits:15'
                // 'unique:users,phone'
            ],
        ];
    }
}