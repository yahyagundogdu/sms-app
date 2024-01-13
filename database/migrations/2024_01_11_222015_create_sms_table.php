<?php

use App\Enums\SmsStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('country_id');
            $table->string('number', 15);
            $table->string('message');
            $table->boolean('status')->nullable()->default(NULL);
            $table->timestamp('send_time')->nullable()->comment('Gönderilme Tarihi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
