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
        Schema::connection('mongodb')->create('history_card_object_services', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->string('type');
            $collection->string('name');
            $collection->string('executor');
            $collection->string('responsible');
            $collection->string('periodicity');
            $collection->date('previous_maintenance_date');
            $collection->date('planned_maintenance_date');
            $collection->string('calendar_color');
            $collection->string('consumable_materials');
            $collection->boolean('checked')->nullable()->default(null);

            $collection->objectId('card_id'); // Тип objectId для связи с таблицей card_object_main
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_object_services');
    }
};
