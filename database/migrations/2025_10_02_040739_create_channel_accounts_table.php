<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index();
            $table->string('name');
            $table->json('credentials');
            $table->json('meta')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('channel_accounts');
    }
};
