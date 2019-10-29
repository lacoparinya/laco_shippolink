<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransM extends Model
{
    protected $fillable = [
        'filename', 'serverpath', 'type', 'total_usd', 'total_bht', 'exchange_rate', 'note'
    ];

    public function banktransd()
    {
        return $this->hasMany('App\BankTransD', 'bank_trans_m_id');
    }

}
