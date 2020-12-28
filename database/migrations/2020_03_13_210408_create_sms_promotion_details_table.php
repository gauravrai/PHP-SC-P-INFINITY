<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsPromotionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_promotion_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('sms_promotion_id');
            $table->foreign('sms_promotion_id')->references('id')->on('sms_promotions');

            $table->string('contact_number');
            $table->string('api_status')->nullable();
            $table->boolean('issent')->default(0);
            $table->dateTime('sent_at')->nullable();
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
        Schema::dropIfExists('sms_promotion_details');
    }
}
