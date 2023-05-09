<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->string('thumbnail');
            $table->string('about');
            $table->unsignedDouble('price');
            $table->unsignedDouble('rating', 5);
            $table->unsignedBigInteger('review');
            $table->unsignedSmallInteger('start_time_available')->nullable();
            $table->unsignedSmallInteger('end_time_available')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('active_days');
            $table->enum('category', ['tutoring', 'housekeeping', 'rentafriend', 'other']);
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