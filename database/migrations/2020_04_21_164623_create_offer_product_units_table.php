<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferProductUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_product_units', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('offer_id')->unsigned()->index();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');

            $table->bigInteger('product_unit_id')->unsigned()->index();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('cascade');

            $table->unique(['offer_id','product_unit_id']);

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
        Schema::dropIfExists('offer_product_units');
    }
}
