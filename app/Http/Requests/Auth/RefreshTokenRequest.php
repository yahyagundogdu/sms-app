<?php

namespace App\Http\Requests\Auth;

use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    use RequestValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'refresh_token' => [
                'description' => 'Refresh Token',
                'example' => '',
            ]
        ];
    }
}
