<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('upload_date');
            $table->integer('shipping_id');
            $table->integer('no');
            $table->string('product_name');
            $table->integer('qty');
            $table->date('INV_DATE');
            $table->string('inv_no');
            $table->string('trans_no');
            $table->string('shipping_ref');
            $table->float('ex_rate');
            $table->float('FOB');
            $table->float('BHT');
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
        Schema::dropIfExists('ship_datas');
    }
}
