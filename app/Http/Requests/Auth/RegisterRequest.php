<?php

namespace App\Http\Requests\Auth;

use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'email' => 'required|email',
            'user_name' => 'required|string|unique:customers,user_name',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'firstname' => [
                'description' => 'İsim',
                'example' => 'Hakan',
            ],
            'lastname' => [
                'description' => 'Soyisim',
                'example' => 'Özbay',
            ],
            'email' => [
                'description' => "Eposta Adresi",
                'example' => 'test@gmail.com',
            ],
            'user_name' => [
                'description' => "Kullanıcı Adı",
                'example' => 'testkullanici',
            ],
        ];
    }
}
