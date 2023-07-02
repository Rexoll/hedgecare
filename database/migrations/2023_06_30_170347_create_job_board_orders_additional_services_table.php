<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_board_orders_additional_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('rentafriend_id')->nullable();
            $table->unsignedBigInteger('housekeeping_id')->nullable();
            $table->unsignedBigInteger('maintenance_id')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('job_board_orders')->onDelete('cascade');
            $table->foreign('rentafriend_id')->references('id')->on('rentAfriend_additional_services')->onDelete('cascade');
            $table->foreign('housekeeping_id')->references('id')->on('housekeeping_additional_services')->onDelete('cascade');
            $table->foreign('maintenance_id')->references('id')->on('maintenance_additional_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_board_orders_additional_services');
    }
};
