<?php

namespace App\Http\Requests\Auth;

use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    use RequestValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !!auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'app_version' => 'nullable',
            'device_id' => 'nullable',
            'device_model' => 'nullable',
            'device_version' => 'nullable',
            'device_type' => 'nullable',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'app_version' => [
                'description' => 'Uygulama Versionu',
                'example' => 1.0
            ],
            'device_id' => [
                'description' => 'Cihaz ID',
                'example' => '123456'
            ],
            'device_model' => [
                'description' => 'Cihaz Modeli',
                'example' => 'iPhone 12 Pro Max'
            ],
            'device_version' => [
                'description' => 'Cihaz Versiyonu',
                'example' => '14.4'
            ],
            'device_type' => [
                'description' => 'Cihaz Tipi ; I:IOS, A:Android, O:Other',
                'example' => 'A'
            ],
        ];
    }
}
