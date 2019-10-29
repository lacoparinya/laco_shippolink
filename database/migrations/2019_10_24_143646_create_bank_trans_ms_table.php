<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransMsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_trans_ms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('serverpath');
            $table->string('type')->nullable;
            $table->float('total_usd');
            $table->float('total_bht');
            $table->float('exchange_rate');
            $table->text('note')->nullable;
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
        Schema::dropIfExists('bank_trans_ms');
    }
}
