<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerDevice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'customer_id',
        'app_version',
        'device_id',
        'device_model',
        'device_version',
        'device_type',
        'is_active',
        'device_user_name',
        'open_udid'
    ];

    protected static bool $logFillable = true;

    protected static string $logName = 'CustomerDevice';

    protected static bool $logOnlyDirty = true;

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }
}
