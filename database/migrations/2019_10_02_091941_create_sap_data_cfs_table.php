<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapDataCfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sap_data_cfs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('upload_date');
            $table->string('billing_type');
            $table->string('sale2party');
            $table->string('payer');
            $table->date('billing_date');
            $table->string('org');
            $table->string('channel');
            $table->string('billing_cat');
            $table->string('billing_doc');
            $table->string('sd_doc_cat');
            $table->string('posting_status');
            $table->string('created_by');
            $table->float('net_value');
            $table->string('doc_currency');
            $table->float('tax_amount');
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
        Schema::dropIfExists('sap_data_cfs');
    }
}
