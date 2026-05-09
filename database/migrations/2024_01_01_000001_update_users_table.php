<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['user', 'model', 'admin'])->default('user')->after('phone');
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('role');
            $table->string('otp')->nullable()->after('wallet_balance');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->boolean('phone_verified')->default(false)->after('otp_expires_at');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->after('phone_verified');
            $table->string('avatar')->nullable()->after('status');
            $table->string('country')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'wallet_balance', 'otp', 'otp_expires_at', 'phone_verified', 'status', 'avatar', 'country']);
        });
    }
};
