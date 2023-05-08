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
        Schema::create('housekeeping_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->enum('order_type', ['individual', 'business']);
            $table->string('street_address');
            $table->string('detail_address')->nullable();
            $table->string('service_hours');
            $table->text('detail_service');
            $table->unsignedBigInteger('provider_id');
            $table->timestamp('start_date');
            $table->string('pay_with_paypal')->nullable();
            $table->string('pay_with_card')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('category_id')->references('id')->on('housekeeping_categories');
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
        Schema::dropIfExists('housekeeping_orders');
    }
};