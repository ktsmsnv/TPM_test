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
        Schema::connection('mongodb')->table('card_object_service_types', function (Blueprint $collection) {
            // Добавляем новый столбец 'checked'
            $collection->boolean('checked')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('card_object_service_types', function (Blueprint $collection) {
            // Удаляем добавленный столбец 'checked'
            $collection->dropColumn('checked');
        });
    }
};
