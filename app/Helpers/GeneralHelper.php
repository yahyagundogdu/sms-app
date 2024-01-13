<?php

use App\Models\Customer;
use App\Models\QrCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// Telefon numarasını formata göre temizler.
if (!function_exists('clearPhoneNumber')) {

    function clearPhoneNumber($phoneNumber): array|bool|string|null
    {
        // Telefon numarasındaki tüm olası olmayan karakterleri temizle
        $cleanedNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // 11 karakterli ise ve başında sıfır varsa sıfırı temizle
        if (strlen($cleanedNumber) === 11 && str_starts_with($cleanedNumber, '0')) {
            $cleanedNumber = substr($cleanedNumber, 1);
        }

        // Temizlenmiş numaranın uzunluğuna göre sonucu döndür
        if (strlen($cleanedNumber) === 10) {
            return $cleanedNumber;
        } else {
            return false;
        }
    }
}

// Sms için doğrulama kodu oluşturur.
if (!function_exists('generateSmsCode')) {
    function generateSmsCode($test): string
    {
        return rand(10000, 99999);
    }
}

// Süre geri sayım
if (!function_exists('countDown')) {
    function countDown($date, $finishSecond = 120): array
    {
        $currentTime = time();
        $lastSentTime = strtotime($date);
        $remainingTime = $finishSecond - ($currentTime - $lastSentTime);
        $hours = floor($remainingTime / 3600);
        $minutes = floor(($remainingTime % 3600) / 60);
        $seconds = $remainingTime % 60;

        $stringTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return [
            'remainingTime' => $remainingTime,
            'stringTime' => $stringTime
        ];
    }
}

// Namespace sonundaki sınıfı döndürür.
if (!function_exists('getClassName')) {
    function getClassName($class): string
    {
        $className = explode('\\', $class);
        return end($className);
    }
}

// Photo Generator
if (!function_exists('photoUrlGenerator')) {
    function photoUrlGenerator($fullName): string
    {
        return "https://ui-avatars.com/api/?name={$fullName}&background=random";
    }
}

// Carbon Parse Format
if (!function_exists('carbonParseFormat')) {
    function carbonParseFormat($date, $format): string
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('distance')) {
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $radius = 6371;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $radius * $c;

        return $distance;
    }
}
