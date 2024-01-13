<?php

namespace App\Enums;

enum ActiveEnum: int
{
    case ACTIVE = 1;
    case PASSIVE = 0;

    public function alias(): string
    {
        return match ($this) {
            self::ACTIVE => __('Aktif'),
            self::PASSIVE => __('Pasif'),
        };
    }
}
