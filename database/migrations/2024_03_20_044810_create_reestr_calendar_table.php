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
        Schema::create('reestr_calendar', function (Blueprint $table) {
            $table->id();
            $table->string('typeInfrastructCalend');
            $table->string('nameObjectCalend');
            $table->string('invFactNum');
            $table->string('instPlace');
            $table->string('typeServ');
            $table->year('calendarYear');
            $table->date('dateCreationCalend');
            $table->date('dateLastSaveCalend');
            $table->date('dateArchivCalend');
            $table->string('curatorCalend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reestr_calendar');
    }
};
