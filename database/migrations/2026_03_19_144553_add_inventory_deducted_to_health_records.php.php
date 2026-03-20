<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            if (!Schema::hasColumn('health_records', 'quantity_requested')) {
                $table->integer('quantity_requested')
                      ->nullable()
                      ->after('duration_days')
                      ->comment('Number of medicine units requested by the patient');
            }
            if (!Schema::hasColumn('health_records', 'inventory_deducted')) {
                $table->boolean('inventory_deducted')
                      ->default(false)
                      ->after('quantity_requested');
            }
            if (!Schema::hasColumn('health_records', 'inventory_deducted_at')) {
                $table->timestamp('inventory_deducted_at')
                      ->nullable()
                      ->after('inventory_deducted');
            }
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            foreach (['quantity_requested', 'inventory_deducted', 'inventory_deducted_at'] as $col) {
                if (Schema::hasColumn('health_records', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};