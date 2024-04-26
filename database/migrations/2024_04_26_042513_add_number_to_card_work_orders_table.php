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
        Schema::connection('mongodb')->table('card_work_orders', function (Blueprint $table) {
            // Добавляем новый столбец 'number'
            $table->string('number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('card_work_orders', function (Blueprint $table) {
            // Удаляем столбец 'number', если он существует
            $table->dropColumn('number');
        });
    }
};
