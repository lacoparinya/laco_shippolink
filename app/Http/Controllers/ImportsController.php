<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

use App\ShipData;
use App\SapDataCf;
use App\PoData;
use App\PoDataDetail;

class ImportsController extends Controller
{
    public function shipdata(){
        return view('imports.shipdata');
    }

    public function processAction(Request $request){

        if ($request->input('import_type') == 'shipdata') {

            if ($request->hasFile('uploadfile')) {
            // var_dump($requestData['uploadfile']);

                $filename = "fileName_" . time() . '_' . $request->file('uploadfile')->getClientOriginalName();

                //echo $filename;

                $uploadfilepath = $request->file('uploadfile')->storeAs('public/excel', $filename );

                // echo $uploadfilepath;

                $realPathFile =  Storage::disk('public')->path('excel');
                

                $realfile = $realPathFile ."\\" . $filename;
                Excel::load($realfile, function ($reader) {

                    $reader->each(function ($row) {

                        $tmp = array();
                        $mydate = str_replace('/', '-', $row->inv_date);
                        
                        $tmp['upload_date'] = date('Y-m-d');
                        $tmp['shipping_id'] = 1;
                        $tmp['no'] = $row->no;
                        $tmp['product_name'] = trim($row->product_name);
                        $tmp['qty'] = $row->quantity;
                        $tmp['INV_DATE'] =  date('Y-m-d',strtotime($mydate));
                        $tmp['inv_no'] = trim($row->{'invoice_no.'});
                        $tmp['trans_no'] = trim($row->trans);
                        $tmp['shipping_ref'] = trim($row->{'shipping_ref.'});
                        $tmp['ex_rate'] = $row->ex_rate;
                        $tmp['FOB'] = $row->fob;
                        $tmp['BHT'] = $row->baht;
                        $tmp['status'] = trim($row->status);

                        $chk = ShipData::where('product_name', trim($row->product_name))
                            ->where('INV_DATE', date('Y-m-d', strtotime($mydate)))
                            ->where('inv_no', trim($row->{'invoice_no.'}))
                            ->where('trans_no', trim($row->trans))
                            ->where('qty', $row->quantity)
                            ->where('shipping_ref', trim($row->{'shipping_ref.'}))->first();
                        


                        if(!empty($chk)){
                            $chk->update($tmp);
                        }else{
                            ShipData::create($tmp);
                        }
                    });

                    
                });

                return redirect('ship-datas')->with('flash_message', ' Import success!!');
                
            }

        }elseif($request->input('import_type') == 'sapcfsdata'){
            if ($request->hasFile('uploadfile')) {
                // var_dump($requestData['uploadfile']);

                $filename = "fileName_" . time() . '_' . $request->file('uploadfile')->getClientOriginalName();

                //echo $filename;

                $uploadfilepath = $request->file('uploadfile')->storeAs('public/excel', $filename);

                // echo $uploadfilepath;

                $realPathFile =  Storage::disk('public')->path('excel');


                $realfile = $realPathFile . "\\" . $filename;
                Excel::load($realfile, function ($reader) {

                    $reader->each(function ($row) {

                        $tmp = array();

                        $tmp['upload_date'] = date('Y-m-d');
                        $tmp['billing_type'] = trim($row->billing_type);
                        $tmp['sale2party'] = trim($row->sold_to_party);
                        $tmp['payer'] = trim($row->payer);
                        $tmp['billing_date'] = $row->billing_date;
                        $tmp['org'] =  $row->sales_organization;
                        $tmp['channel'] = trim($row->distribution_channel);
                        $tmp['billing_cat'] = trim($row->billing_category);
                        $tmp['billing_doc'] = trim($row->billing_document);
                        $tmp['sd_doc_cat'] = $row->{'sd_document_categ.'};
                        $tmp['posting_status'] = $row->posting_status;
                        $tmp['created_by'] = $row->created_by;
                        $tmp['net_value'] = $row->net_value;
                        $tmp['doc_currency'] = trim($row->document_currency);
                        $tmp['tax_amount'] = $row->tax_amount;
                        

                        $chk = SapDataCf::where('billing_doc',trim($row->billing_document))->first();

                        if (!empty($chk)) {
                            $chk->update($tmp);
                        } else {
                            SapDataCf::create($tmp);
                        }
                    });
                });

               return redirect('sap-data-cfs')->with('flash_message', ' Import success!!');
            }
            } elseif ($request->input('import_type') == 'podata') {
                if ($request->hasFile('uploadfile')) {
                    // var_dump($requestData['uploadfile']);

                    $filename = "fileName_" . time() . '_' . $request->file('uploadfile')->getClientOriginalName();

                   

                    $uploadfilepath = $request->file('uploadfile')->storeAs('public/excel', $filename);

                    

                    $realPathFile =  Storage::disk('public')->path('excel');


                    $realfile = $realPathFile . "\\" . $filename;

                    echo $realfile;
                    Excel::load($realfile, function ($reader) {

                        $reader->each(function ($row) {
                            var_dump($row);
                            echo "Run<br/><br/>";
                            if(!empty($row->order)){
                            
                            //check create PO Master
                            $chkmaster = PoData::where('order_name',$row->order)->first();

                            if(!empty($chkmaster)){

                            }else{
                               // var_dump($row);
                                $tmp = array();

                                $tmp['upload_date'] = date('Y-m-d');
                                $tmp['CSN'] = trim($row->csn);
                                $tmp['order_name'] = trim($row->order);
                                $tmp['loading_date'] = trim($row->loading);
                                $tmp['sale_order_name'] = trim($row->sales_order);
                                $tmp['inv_name'] = trim($row->invoice);
                                $tmp['billing_name'] = trim($row->billing);
                                $tmp['ref_ship_name'] = trim($row->{"ref._shipping"});
                                $tmp['trans_name'] = 'WAIT';
                                $tmp['candf'] = 0;
                                $tmp['status'] = 'WAIT';

                                $chkmaster = PoData::create($tmp);

                                echo $chkmaster->id;
                            }

                            //insert Details
                            $chkdetail = PoDataDetail::where('po_data_id', $chkmaster->id)
                                            ->where('product_code', $row->product)
                                            ->where('qty', $row->quantity)->first();

                            if(!empty($chkdetail)){

                            }else{
                                $tmp2 = array();

                                $tmp2['po_data_id'] = $chkmaster->id;
                                $tmp2['product_name'] = $row->product_name;
                                $tmp2['product_code'] = $row->product;
                                $tmp2['weight'] = $row->weight;
                                $tmp2['qty'] = $row->quantity;
                                $tmp2['unit_name'] = $row->unit;
                                $tmp2['tax_rate'] = $row->tax;
                                $tmp2['use'] = 'Yes';
                                $tmp2['status'] = 'WAIT';

                                $chkdetail = PoDataDetail::create($tmp2);
                            }
                        }
                        });
                    });

                   //return redirect('po-datas')->with('flash_message', ' Import success!!');
                }

        }
    }

    public function sapcfsdata()
    {
        return view('imports.sapcfsdata');
    }

    public function podata()
    {
        return view('imports.podata');
    }

}
