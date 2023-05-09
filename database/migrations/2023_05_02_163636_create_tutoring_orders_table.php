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
        Schema::create('tutoring_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type', ['individual', 'business']);
            $table->enum('environment', ['individual', 'group_lessons']);
            $table->string('street_address');
            $table->string('detail_address')->nullable();
            $table->string('session');
            $table->date('start_date');
            $table->string('tutoring_hours');
            $table->unsignedBigInteger('provider_id');
            $table->string('pay_with_paypal')->nullable();
            $table->string('pay_with_card')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutoring_orders');
    }
};
