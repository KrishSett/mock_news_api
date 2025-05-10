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
        Schema::create('guest_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->string('visitor_id', 75)->index();
            $table->ipAddress('ip_address')->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_tokens');
    }
};
