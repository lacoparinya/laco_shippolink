<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransD extends Model
{
    protected $fillable = [
        'bank_trans_m_id', 'po_data_id', 'other_case', 'income_usd', 'income_bht'
    ];

    public function podata()
    {
        return $this->hasOne('App\PoData', 'id', 'po_data_id');
    }
}
