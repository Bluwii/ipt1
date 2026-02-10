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
        Schema::table('health_records', function (Blueprint $table) {
            $table->string('prescription_image')->nullable()->after('instructions');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('prescription_image');
            $table->text('admin_notes')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $table->dropColumn(['prescription_image', 'approval_status', 'admin_notes', 'approved_at']);
        });
    }
};