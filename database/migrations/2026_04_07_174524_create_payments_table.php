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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->enum('method', ['Midtrans', 'Manual_Transfer', 'On_Site']);
            $table->string('snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('proof_image')->nullable();
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
