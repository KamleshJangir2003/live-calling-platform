<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('model_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('country')->nullable();
            $table->string('languages')->nullable();
            $table->decimal('audio_price', 8, 2)->default(1.00);
            $table->decimal('video_price', 8, 2)->default(2.00);
            $table->boolean('online_status')->default(false);
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('kyc_document')->nullable();
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->decimal('pending_withdrawal', 10, 2)->default(0);
            $table->string('profile_photo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->integer('total_calls')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_profiles');
    }
};
