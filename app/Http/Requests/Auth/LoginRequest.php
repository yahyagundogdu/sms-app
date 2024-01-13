<?php

namespace App\Http\Requests\Auth;

use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'user_name' => 'required|exists:customers,user_name',
            'password' => 'required|min:6',
            'device_id' => 'nullable',
            'device_model' => 'nullable',
            'device_version' => 'nullable',
            'device_type' => 'nullable',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'user_name' => [
                'description' => 'Kullanıcı Adı',
                'example' => 'programmer1453',
            ],
            'password' => [
                'description' => 'Şifre',
                'example' => 123456
            ],
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
            ]
        ];
    }
}
