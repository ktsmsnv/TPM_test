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
        Schema::connection('mongodb')->create('history_card_graph_table', function (Blueprint $table) {
            $table->index('card_graph_id');
            $table->string('name');
            $table->string('infrastructure_type');
            $table->string('curator')->nullable();
            $table->integer('year_action')->nullable();
            $table->date('date_create');
            $table->date('date_last_save');
            $table->date('date_archive')->nullable();
            $table->json('cards_ids');
//            $table->json('card_graph_id');
            $table->foreign('card_graph_id')->references('_id')->on('card_graph_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_graph_table');
    }
};
