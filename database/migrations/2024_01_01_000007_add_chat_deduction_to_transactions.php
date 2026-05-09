<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('recharge','call_deduction','chat_deduction','earning','withdrawal','refund','commission') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('recharge','call_deduction','earning','withdrawal','refund','commission') NOT NULL");
    }
};
