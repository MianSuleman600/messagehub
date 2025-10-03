<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('failed_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index();
            $table->json('payload');
            $table->json('headers');
            $table->unsignedInteger('status')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('failed_webhooks');
    }
};
