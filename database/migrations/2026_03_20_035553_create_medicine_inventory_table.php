<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g. "Paracetamol 500mg"
            $table->string('category')->nullable();          // e.g. "Pain Relief / Fever"
            $table->integer('stock')->default(0);            // current stock
            $table->string('unit')->default('tablets');      // tablets / capsules / etc.
            $table->boolean('is_available')->default(true);  // show/hide from patient list
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_inventory');
    }
};