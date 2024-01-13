<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

trait RequestValidationTrait
{

    public function attributes(): array
    {
        return [
            'first_name' => __("Ad"),
            'last_name' => __("Soyad"),
            'email' => __("E-Posta"),
            'phone' => __("Telefon"),
            'password' => __("Parola"),
            'password_confirmation' => __("Parola Doğrulama"),
            'agreement' => __('Üyelik sözleşmesi'),
            'login_password' => __("Parola"),
            'login_email' => __("E-Posta Adresi"),
            'udid_id' => "UDID",
            'device_type' => __("Device Type"),
            'product_id' => __('Ürün'),
            'quantity' => __('Adet'),
            'notification_id' => __('Bildirim ID'),
            'status' => __('Durum'),
            'name_surname' => __('Ad Soyad'),
            'user_name' => __('Kullanıcı Adı'),
            'identification_number' => __('TC Kimlik'),
            'city_id' => __('İl'),
            'district_id' => __('İlçe'),
            'email_address' => __('E-Posta'),
            'category_id' => __('Kategori'),
            'subcategory_id' => __('Alt Kategori'),
            'code' => __('Kod'),
            'firstname' => __('İsim'),
            'lastname' => __('Soyisim'),
            'phone_number' => __('Telefon'),
            'type' => __('Tip'),
            "agreement.*.value" => __("Sözleşme"),
            "city_id" => __("Şehir"),
            "district_id" => __("İlçe"),
            "neighborhood_id" => __("Mahalle"),
            "message"=>__('Mesaj'),
            "numbers"=>__('Numara'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(outputError(data: $validator->errors(), message: __('Hata! Lütfen Formu Kontrol Ediniz'), statusCode: 422));
    }
}
