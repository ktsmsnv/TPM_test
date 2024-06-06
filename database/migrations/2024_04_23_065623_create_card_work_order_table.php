<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mongodb')->create('card_work_order', function (Blueprint $collection) {
            $collection->index('card_id');
            $collection->date('date_create');
//            $collection->date('date_last_save');
            $collection->date('date_fact')->nullable();
            $collection->string('status');
            $collection->date('planned_maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('card_work_order');
    }
};
