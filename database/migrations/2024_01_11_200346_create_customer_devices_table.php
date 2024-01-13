<?php

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
        Schema::create('customer_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id');
            $table->string('app_version')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_model')->nullable();
            $table->string('device_version')->nullable();
            $table->string('device_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('device_user_name', 255)->nullable();
            $table->string('open_udid', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_devices');
    }
};
