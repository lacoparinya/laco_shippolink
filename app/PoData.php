<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoData extends Model
{
    protected $fillable = [
        'upload_date', 'CSN', 'csn_id', 'order_name', 'order_id', 'loading_date', 'sale_order_name', 
        'sale_order_id', 'inv_name', 'inv_id', 'billing_name', 'billing_id', 'trans_name', 'trans_id', 
        'ref_ship_name', 'ref_ship_id', 'candf', 'note', 'status','print_status','main_status','status_trans','status_cnf'];

    public function podatadetails()
    {
        return $this->hasMany('App\PoDataDetail', 'po_data_id');
    }

    public function fileupload()
    {
        return $this->hasMany('App\FileUpload', 'po_data_id');
    }


}
