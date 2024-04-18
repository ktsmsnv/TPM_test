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
        Schema::connection('mongodb')->create('card_graph_main_graphtpm', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->string('name_object');
            $collection->string('factory_num');
            $collection->string('january');
            $collection->string('february');
            $collection->string('march');
            $collection->string('april');
            $collection->string('may');
            $collection->string('june');
            $collection->string('july');
            $collection->string('august');
            $collection->string('september');
            $collection->string('october');
            $collection->string('november');
            $collection->string('december');

            $collection->foreign('card_id')->references('_id')->on('card_object_main'); // Тип objectId для связи с таблицей card_graph_main
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_graph_main_graphtpm');
    }
};
