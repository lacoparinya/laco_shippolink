<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoDataDetail extends Model
{
    protected $fillable = ['po_data_id'
      ,'product_name'
      ,'product_id'
      ,'product_code'
      ,'weight'
      ,'qty'
      ,'unit_name'
      ,'unit_id'
      ,'use'
      ,'status'];
}
