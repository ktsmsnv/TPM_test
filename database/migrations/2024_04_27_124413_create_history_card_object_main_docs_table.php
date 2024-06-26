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
        Schema::connection('mongodb')->create('history_card_object_main_docs', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->string('name');
            $collection->string('file_name');
            $collection->binary('content'); // Если хотите хранить файлы напрямую в коллекции, используйте тип binary
            // Или $collection->string('content'); // Если предпочитаете хранить ссылки на файлы
            $collection->objectId('card_id'); // Тип objectId для связи с таблицей card_object_main
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_card_object_main_docs');
    }
};
