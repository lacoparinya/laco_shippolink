<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipData extends Model
{
    protected $fillable = ['upload_date'
      ,'shipping_id'
      ,'no'
      ,'product_name'
      ,'qty'
      ,'INV_DATE'
      ,'inv_no'
      ,'trans_no'
      ,'shipping_ref'
      ,'ex_rate'
      ,'FOB'
      ,'BHT'
      ,'status'];

  public function podatadetail()
  {
    return $this->hasOne('App\PoDataDetail', 'ship_data_id', 'id');
  }

}
