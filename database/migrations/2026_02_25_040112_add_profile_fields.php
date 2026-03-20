<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable()->after('birthdate');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'purok_no')) {
                $table->string('purok_no', 10)->nullable()->after('phone_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birthdate', 'age', 'gender', 'phone_number', 'purok_no']);
        });
    }
};