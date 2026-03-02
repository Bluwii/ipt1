<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            if (!Schema::hasColumn('health_records', 'source_appointment_id')) {
                $table->unsignedBigInteger('source_appointment_id')
                      ->nullable()
                      ->after('admin_notes')
                      ->comment('Links auto-created record back to its source appointment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            if (Schema::hasColumn('health_records', 'source_appointment_id')) {
                $table->dropColumn('source_appointment_id');
            }
        });
    }
};