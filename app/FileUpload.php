<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = [
            'filename'
            ,'serverpath'
            ,'type'
            ,'transno'
            ,'invno'
            ,'po_data_id'
            ,'status'];
}
