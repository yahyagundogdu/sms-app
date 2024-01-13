<?php

namespace App\Jobs;

use App\Enums\SmsStatusEnum;
use App\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SmsSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Sms $sms;

    /**
     * Create a new job instance.
     */
    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sms->send_time = now();
        // SMS servis sağlayıcı olmadığı için sonucu random yazdırdım
        $this->sms->status = $this->sms->number % 2 == 1 ? SmsStatusEnum::FAIL->value : SmsStatusEnum::SEND->value;
        $this->sms->update();
        sleep(1);
    }
}
