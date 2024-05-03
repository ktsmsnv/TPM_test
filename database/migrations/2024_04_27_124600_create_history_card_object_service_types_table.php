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
        Schema::connection('mongodb')->create('history_card_object_service_types', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->index('card_services_id');
            $collection->string('type_work');
            $collection->objectId('card_id'); // Тип objectId для связи с таблицей card_object_main
            $collection->objectId('card_services_id'); // Тип objectId для связи с таблицей card_object_services
            $collection->boolean('checked')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_object_service_types');
    }
};
