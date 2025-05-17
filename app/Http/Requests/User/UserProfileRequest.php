<?php

namespace App\Http\Requests\User;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidatePasswordUpdate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserProfileRequest extends FormRequest
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
        $user = Auth::guard('user')->user();
        return [
            'name' => 'sometimes|string',
            'email' => 'nullable|string',
            "image" => 'sometimes',
            "phone" => "sometimes|unique:users,phone," . $user->id,
            'current_password' => [
                'nullable',
                'string',
                new ValidatePasswordUpdate('user'),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                new ValidatePasswordUpdate('user'),
            ],

        ];
    }
}