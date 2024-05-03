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
        Schema::connection('mongodb')->create('history_card_object_main', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->string('infrastructure');
            $collection->string('name');
            $collection->string('number');
            $collection->string('location');
            $collection->date('date_arrival');
            $collection->date('date_usage');
            $collection->date('date_cert_end');
            $collection->date('date_usage_end');
            $collection->file('image')->nullable();
            $collection->foreign('card_id')->references('_id')->on('card_object_main');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_object_main');
    }
};
