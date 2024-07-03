<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inverter_statuses', function (Blueprint $table) {
            $table->dateTime('recorded_at')->nullable()->after('pdc');
        });

        DB::table('inverter_statuses')->update(['recorded_at' => DB::raw('created_at')]);

        Schema::table('inverter_statuses', function (Blueprint $table) {
            $table->dateTime('recorded_at')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inverter_statuses', function (Blueprint $table) {
            $table->dropColumn('recorded_at');
        });
    }
};
