<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index(); // e.g., tiktok, whatsapp, meta
            $table->string('external_id')->nullable()->index(); // unique ID from provider
            $table->string('name');
            $table->json('credentials'); // access tokens, refresh tokens
            $table->json('meta')->nullable(); // extra info like avatar, username, etc.
            $table->string('webhook_secret')->nullable(); // for verification
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // allow soft deletes
        });
    }

    public function down(): void {
        Schema::dropIfExists('channel_accounts');
    }
};
