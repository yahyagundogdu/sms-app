<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes, LogsActivity, HasApiTokens, Notifiable, HasFactory;


    protected $fillable = [
        "user_name",
        "email",
        "first_name",
        "last_name",
        "password",
        "is_active",
        "last_login_at",
    ];
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function findForPassport($username)
    {
        $loginField = 'user_name';

        // Panel kullanıcıları için eposta alanı kontrol ediliyor
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
        }

        return $this->query()->where($loginField, $username)->first();
    }

    public function devices()
    {
        return $this->hasMany(CustomerDevice::class);
    }
}
