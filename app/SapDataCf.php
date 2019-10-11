<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SapDataCf extends Model
{
    protected $fillable = ['upload_date'
      ,'billing_type'
      ,'sale2party'
      ,'payer'
      ,'billing_date'
      ,'org'
      ,'channel'
      ,'billing_cat'
      ,'billing_doc'
      ,'sd_doc_cat'
      ,'posting_status'
      ,'created_by'
      ,'net_value'
      ,'doc_currency'
      ,'tax_amount'];
}
