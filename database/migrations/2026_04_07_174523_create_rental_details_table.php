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
        Schema::create('rental_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('costume_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);

            $table->unique(['rental_id', 'costume_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_details');
    }
};
