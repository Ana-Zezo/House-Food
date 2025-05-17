<?php

namespace App\Http\Requests\Chef;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateChefRequest extends FormRequest
{

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
			'name' => ['required', 'string'],
			'phone' => ['required', 'string'],
			'password' => ['required', 'string', 'confirmed'],
			'email' => ['required', 'email', 'string',],
			'image' => ['required', 'image'],
			'bio' => ['required', 'string'],
		];
	}
}