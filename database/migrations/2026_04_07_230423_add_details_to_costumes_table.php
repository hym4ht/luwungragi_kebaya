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
        Schema::table('costumes', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('materials')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('sizes')->nullable(); // comma-separated or S,M,L,XL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costumes', function (Blueprint $table) {
            $table->dropColumn(['image', 'description', 'materials', 'care_instructions', 'sizes']);
        });
    }
};
