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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('returned_date');
            $table->decimal('fine_amount', 12, 2)->default(0);
            $table->string('return_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
