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
        Schema::create('job_board_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('session_id')->nullable();
            $table->unsignedMediumInteger('from_hour')->default(1);
            $table->unsignedMediumInteger('expected_hour')->default(1);
            $table->enum('service_name', ['housekeeping', 'rentafriend', 'maintenance']);
            $table->string('street_address');
            $table->string('detail_address')->nullable();
            $table->date('start_date');
            $table->text('detail_service');
            $table->enum('status', ['not_paid', 'paid', 'active', 'cancel', 'done']);
            $table->double('sub_total')->nullable();
            $table->double('tax')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('pay_with_paypal')->nullable();
            $table->string('pay_with_card')->nullable();
            $table->float('rating')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_board_orders');
    }
};
