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
        Schema::connection('mongodb')->create('history_card_work_order', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->string('card_object_services_id')->nullable();
            $collection->date('date_create');
//            $collection->date('date_last_save');
            $collection->date('date_fact')->nullable();
            $collection->string('status');
            $collection->string('number')->nullable();
            $collection->date('planned_maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_work_order');
    }
};
