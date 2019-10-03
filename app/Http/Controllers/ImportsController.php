<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use App\ShipData;

class ImportsController extends Controller
{
    public function shipdata(){
        return view('imports.shipdata');
    }

    public function processAction(Request $request){
        if ($request->hasFile('uploadfile')) {
           // var_dump($requestData['uploadfile']);

            $filename = "fileName_" . time() . '_' . $request->file('uploadfile')->getClientOriginalName();

            //echo $filename;

            $uploadfilepath = $request->file('uploadfile')->storeAs('public/excel', $filename );

            // echo $uploadfilepath;

            $realPathFile =  Storage::disk('public')->path('excel');
            

            $realfile = $realPathFile ."\\" . $filename;
            Excel::load($realfile, function ($reader) {

               // $result = $reader->toArray();

                $reader->each(function ($row) {

                    //  var_dump($row);
                    $tmp = array();

                    $tmp['upload_date'] = date('Y-m-d');
                    $tmp['shipping_id'] = 1;
                    $tmp['no'] = $row->no;
                    $tmp['product_name'] = $row->product_name;
                    $tmp['qty'] = $row->quantity;
                    $tmp['INV_DATE'] =  date('Y-m-d',strtotime($row->{'inv._date'}));
                    $tmp['inv_no'] = $row->{'invoice_no.'};
                    $tmp['trans_no'] = $row->trans;
                    $tmp['shipping_ref'] = $row->{'shipping_ref.'};
                    $tmp['ex_rate'] = $row->ex_rate;
                    $tmp['FOB'] = $row->fob;
                    $tmp['BHT'] = $row->baht;
                    $tmp['status'] = $row->status;
                    
                    //var_dump($tmp);

                    ShipData::create($tmp);

                });

                
            });
            
        }

        
    }
}
