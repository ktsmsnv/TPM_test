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
        Schema::connection('mongodb')->create('history_card_calendar', function (Blueprint $table) {
            $table->index('card_id');
            $table->index('card_calendar_id');
            $table->date('year');
            $table->date('date_create')->nullable();
            $table->date('date_archive')->nullable();
            $table->foreign('card_calendar_id')->references('_id')->on('card_calendar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_calendar');
    }
};
