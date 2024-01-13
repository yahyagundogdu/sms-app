<?php

namespace App\Enums;

enum SmsStatusEnum: int
{
    case PENDING = 0;
    case FAIL = 1;
    case SEND = 2;

    public function alias(): string
    {
        return match ($this) {
            self::FAIL => __('Hata'),
            self::SEND => __('Gönderildi'),
            self::PENDING => __('İşlem Bekleniyor'),
        };
    }
}
