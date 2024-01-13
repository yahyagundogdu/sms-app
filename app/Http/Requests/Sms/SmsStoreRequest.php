<?php

namespace App\Http\Requests\Sms;

use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class SmsStoreRequest extends FormRequest
{
    use RequestValidationTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "numbers" => "required|array",
            "country_id" => "required|numeric",
            "numbers.*" => "required|string_or_numeric|min:10",
            "message" => "required|min:3"
        ];
    }
}
