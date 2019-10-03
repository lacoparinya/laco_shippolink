<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoDataDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_data_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('po_data_id');
            $table->string('product_name');
            $table->integer('product_id');
            $table->string('product_code');
            $table->float('weight');
            $table->integer('qty');
            $table->string('unit_name');
            $table->integer('unit_id');
            $table->string('use');
            $table->string('status');
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
        Schema::dropIfExists('po_data_details');
    }
}
