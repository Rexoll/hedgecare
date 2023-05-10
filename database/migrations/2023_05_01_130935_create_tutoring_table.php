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
        Schema::create('tutoring', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type', ['individual', 'business']);
            $table->enum('environment', ['individual', 'group_lessons']);
            $table->dateTime('duration');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutoring');
    }
};
