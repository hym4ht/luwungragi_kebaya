<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->date('event_date')->nullable()->after('invoice_number');
        });

        DB::table('rentals')
            ->select(['id', 'rental_date'])
            ->orderBy('id')
            ->chunkById(100, function ($rentals): void {
                foreach ($rentals as $rental) {
                    DB::table('rentals')
                        ->where('id', $rental->id)
                        ->update([
                            'event_date' => Carbon::parse($rental->rental_date)->addDays(2)->toDateString(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('event_date');
        });
    }
};
