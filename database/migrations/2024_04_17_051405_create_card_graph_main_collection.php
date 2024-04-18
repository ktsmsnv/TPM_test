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
        Schema::connection('mongodb')->create('card_graph_main_collection', function (Blueprint $collection) {
            $collection->index('id');
            $collection->string('infrastructure');
            $collection->string('curator');
            $collection->year('year_action');
            $collection->date('date_create');
            $collection->date('date_last_save');
            $collection->date('date_archive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_graph_main_collection');
    }
};
