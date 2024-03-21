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
        Schema::connection('mongodb')->create('card_object_main', function (Blueprint $collection) {
            $collection->index('id');
            $collection->string('infrastructure');
            $collection->string('name');
            $collection->string('number');
            $collection->string('location');
            $collection->date('date_arrival');
            $collection->date('date_usage');
            $collection->date('date_cert_end');
            $collection->date('date_usage_end');
            $collection->file('image')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_object_main_collection');
    }
};
