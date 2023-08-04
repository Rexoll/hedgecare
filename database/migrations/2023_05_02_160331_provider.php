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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('about')->nullable();
            $table->unsignedDouble('price');
            $table->unsignedDouble('rating');
            $table->unsignedBigInteger('review')->nullable();
            $table->time('start_time_available')->nullable();
            $table->time('end_time_available')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('address')->nullable();
            $table->string('active_days')->nullable();
            $table->enum('category', ['tutoring', 'housekeeping', 'rentafriend', 'maintenance', 'other'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
};
