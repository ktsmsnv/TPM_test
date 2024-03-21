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
        Schema::connection('mongodb')->create('card_object_service_types', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->string('type_work');
            $collection->string('name_work');
            // Добавьте остальные столбцы, если они есть в вашей структуре
            $collection->objectId('card_id'); // Тип objectId для связи с таблицей card_object_main
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_object_services_types_collection');
    }
};
