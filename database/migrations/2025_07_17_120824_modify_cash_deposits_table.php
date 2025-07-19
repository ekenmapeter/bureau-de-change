<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cash_deposits', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('depositor_name')->nullable()->after('amount');
            $table->string('depositor_phone')->nullable()->after('depositor_name');
            $table->string('depositor_email')->nullable()->after('depositor_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_deposits', function (Blueprint $table) {
            $table->enum('type', ['opening_balance', 'safeguard'])->after('amount');
            $table->dropColumn(['depositor_name', 'depositor_phone', 'depositor_email']);
        });
    }
};
