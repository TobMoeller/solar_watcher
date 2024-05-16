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
        Schema::create('inverter_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inverter::class)->index();
            $table->decimal('output');
            $table->string('timespan')->nullable()->index();
            $table->date('recorded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inverter_outputs');
    }
};
