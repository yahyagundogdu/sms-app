<?php

namespace App\Http\Resources;

use App\Enums\SmsStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "number" => $this->number,
            "message" => $this->message,
            "created_at" => Carbon::parse($this->created_at)->format('d.m.Y H:i:s'),
            "send_time" => $this->send_time ? Carbon::parse($this->send_time)->format('d.m.Y H:i:s') : '',
            "status" => SmsStatusEnum::from($this->status)->alias(),
        ];
    }
}
