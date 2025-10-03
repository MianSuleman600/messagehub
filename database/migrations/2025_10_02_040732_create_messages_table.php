<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel')->index();
            $table->string('external_id')->nullable()->index();
            $table->string('direction')->index();
            $table->longText('body')->nullable();
            $table->string('status')->default('queued')->index();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamp('received_at')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('messages');
    }
};
