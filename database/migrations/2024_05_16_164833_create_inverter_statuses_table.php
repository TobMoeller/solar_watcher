<?php

use App\Models\Inverter;
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
        Schema::create('inverter_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inverter::class)->index();
            $table->boolean('is_online');
            $table->decimal('udc')->nullable();
            $table->decimal('idc')->nullable();
            $table->decimal('pac')->nullable();
            $table->decimal('pdc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inverter_statuses');
    }
};
