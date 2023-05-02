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
        Schema::create('selected_course', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tutoring_id');
            $table->unsignedBigInteger('course');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tutoring_id')->references('id')->on('tutoring');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_course');
    }
};
