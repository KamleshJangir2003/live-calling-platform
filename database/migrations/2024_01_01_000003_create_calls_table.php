<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->enum('call_type', ['audio', 'video']);
            $table->enum('status', ['initiated', 'accepted', 'rejected', 'missed', 'completed', 'failed'])->default('initiated');
            $table->integer('duration')->default(0); // seconds
            $table->decimal('amount', 8, 2)->default(0);
            $table->decimal('price_per_minute', 8, 2)->default(0);
            $table->string('channel_name')->nullable();
            $table->string('agora_token')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
