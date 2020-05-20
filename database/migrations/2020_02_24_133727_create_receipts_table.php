<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->string('payment_method')->nullable();
            $table->string('note')->nullable();
            $table->string('seller_was')->nullable();
            $table->dateTime('received_at')->nullable();

            $table->bigInteger('bill_id')->unsigned()->index();
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');

             $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

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
        Schema::dropIfExists('receipts');
    }
}
