<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('auto_reply_rules', function (Blueprint $table) {
            $table->id();
            $table->string('channel')->nullable()->index();
            $table->string('matcher_type')->default('contains');
            $table->text('pattern');
            $table->text('reply_template');
            $table->boolean('enabled')->default(true);
            $table->unsignedInteger('priority')->default(100);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('auto_reply_rules');
    }
};
