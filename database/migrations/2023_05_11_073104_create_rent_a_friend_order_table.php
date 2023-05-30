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
        Schema::create('rentAfriend_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedMediumInteger('from_hour')->default(1);
            $table->unsignedMediumInteger('to_hour')->default(2);
            $table->text('detail_service');
            $table->unsignedBigInteger('provider_id');
            $table->date('start_date');
            $table->double('sub_total')->nullable();
            $table->double('tax')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('pay_with_paypal')->nullable();
            $table->string('pay_with_card')->nullable();
            $table->float('review')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('category_id')->references('id')->on('rentAfriend_categories');
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
        Schema::dropIfExists('rentAfriend_order');
    }
};
