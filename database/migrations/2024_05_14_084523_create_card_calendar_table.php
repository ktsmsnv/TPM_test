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
        Schema::connection('mongodb')->create('card_calendar', function (Blueprint $collection) {
            $collection->index('card_id');
         //   $collection->index('curator');
            $collection->date('year');
//            $collection->date('date_last_save');
            $collection->date('date_create')->nullable();
            $collection->date('date_archive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_calendar');
    }
};
