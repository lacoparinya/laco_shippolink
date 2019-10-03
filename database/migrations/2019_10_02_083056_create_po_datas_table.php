<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('upload_date');
            $table->string('CSN');
            $table->integer('csn_id');
            $table->string('order_name');
            $table->integer('order_id');
            $table->date('loading_date');
            $table->string('sale_order_name');
            $table->integer('sale_order_id');
            $table->string('inv_name');
            $table->integer('inv_id');
            $table->string('billing_name');
            $table->integer('billing_id');
            $table->string('trans_name');
            $table->integer('trans_id');
            $table->string('ref_ship_name');
            $table->integer('ref_ship_id');
            $table->float('candf');
            $table->text('note');
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
        Schema::dropIfExists('po_datas');
    }
}
