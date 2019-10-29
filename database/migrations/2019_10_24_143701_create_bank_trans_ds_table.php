<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransDsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_trans_ds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_trans_m_id');
            $table->integer('po_data_id')->nullable;
            $table->string('other_case')->nullable;
            $table->float('income_usd')->nullable;
            $table->float('income_bht')->nullable;
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
        Schema::dropIfExists('bank_trans_ds');
    }
}
