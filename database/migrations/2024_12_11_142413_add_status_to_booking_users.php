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
        if (Schema::hasColumn('booking_users', 'status')) return;
        Schema::table('booking_users', function (Blueprint $table) {
            $table->enum('status', ['no response', 'hadir'])->default('no response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            //
        });
    }
};
