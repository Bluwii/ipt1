<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Minor flag — auto-set by backend when patient age < 18
            $table->boolean('is_minor')->default(false)->after('notes');

            // Guardian / parent information (only required when is_minor = true)
            $table->string('guardian_name')->nullable()->after('is_minor');
            $table->string('guardian_relationship')->nullable()->after('guardian_name'); // mother|father|guardian
            $table->string('guardian_contact')->nullable()->after('guardian_relationship');
            $table->boolean('guardian_consent')->default(false)->after('guardian_contact');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'is_minor',
                'guardian_name',
                'guardian_relationship',
                'guardian_contact',
                'guardian_consent',
            ]);
        });
    }
};