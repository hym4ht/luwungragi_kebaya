<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('payments')
            ->whereNull('payment_type')
            ->orWhereIn('payment_type', ['Bayar di Tempat', 'Transfer Manual'])
            ->update(['payment_type' => 'Midtrans Snap']);
    }

    public function down(): void
    {
        // Historical payment_type values are intentionally not restored.
    }
};
