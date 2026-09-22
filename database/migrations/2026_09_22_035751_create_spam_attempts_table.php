<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spam_attempts', function (Blueprint $table) {
            $table->id();

            $table->string('ip_address', 45)->index();

            $table->text('user_agent')->nullable();

            $table->string('reason')->default('Honeypot validation failed');

            $table->string('route')->nullable();

            $table->string('request_method', 10)->default('POST');

            $table->timestamp('attempted_at')->nullable();

            $table->timestamps();

            $table->index('attempted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spam_attempts');
    }
};