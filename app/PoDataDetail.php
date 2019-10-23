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
      ,'ship_data_id'
      ,'tax_rate'
      ,'use'
      ,'status'];

    public function shipdata()
    {
      return $this->hasOne('App\ShipData', 'id', 'ship_data_id');
    }
}
