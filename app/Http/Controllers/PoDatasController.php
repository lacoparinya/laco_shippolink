<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\PoData;
use App\ShipData;
use App\SapDataCf;
use App\PoDataDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;


class PoDatasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;
        $status = $request->get('status');;

        if (empty($status)) {
            if (!empty($keyword)) {
                $podatas = PoData::where('inv_name','like','%'.$keyword.'%')->orderBy('loading_date', 'desc')->paginate($perPage);
            } else {
                $podatas = PoData::orderBy('loading_date', 'desc')->paginate($perPage);
            }
        }else{
            if (!empty($keyword)) {
                $podatas = PoData::where('inv_name', 'like', '%' . $keyword . '%')->where('main_status', $status)->orderBy('loading_date','desc')->paginate($perPage);
            } else {
                $podatas = PoData::where('main_status', $status)->orderBy('loading_date', 'desc')->paginate($perPage);
            }
        }

        

        return view('po-datas.index', compact('podatas','status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('po-datas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();

        PoData::create($requestData);

        return redirect('po-datas')->with('flash_message', ' added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $podata = PoData::findOrFail($id);

        return view('po-datas.show', compact('podata'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $podata = PoData::findOrFail($id);

        return view('po-datas.edit', compact('podata'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $podata = PoData::findOrFail($id);
        $podata->update($requestData);

        return redirect('po-datas')->with('flash_message', ' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        PoData::destroy($id);

        return redirect('po-datas')->with('flash_message', ' deleted!');
    }

    public function manualProcess($id){
        $podata = PoData::findOrFail($id);
        
        //Map transistion paper
        $transname = "";
        foreach ($podata->podatadetails as $podatadetail) {
            $shipData = ShipData::where('qty', $podatadetail->qty)
                ->where('inv_no', trim($podata->inv_name))->first();

            if(!empty($shipData)){
                $transname = $shipData->trans_no;
            }
            
        }

        if(!empty($transname)){
            $podata->trans_name = $transname;
            $podata->status = 'MAP Trans';
            $podata->update();
        }
        return redirect('po-datas')->with('flash_message', ' Map ใบขน');
    }

    public function AllProcess()
    {
        $podatas = PoData::whereIn('status',['WAIT', 'MAP C & F'])->get();

        foreach ($podatas as $podata) {

            //Map transistion paper
            $transname = "";
            foreach ($podata->podatadetails as $podatadetail) {
                $shipData = ShipData::where('qty', $podatadetail->qty)
                    ->where('inv_no', trim($podata->inv_name))->first();

                if (!empty($shipData)) {
                    $transname = $shipData->trans_no;

                    $tmpdata = PoDataDetail::findOrFail($podatadetail->id);
                    $tmpdata->ship_data_id = $shipData->id;
                    $tmpdata->status = 'MAP ใบขน';
                    $tmpdata->update();
                    

                }
            }

            if (!empty($transname)) {
                $podata->trans_name = $transname;
                if ($podata->status == 'MAP C & F') {
                    $podata->status = 'MAP ใบขน / C & F';
                } elseif ($podata->status == 'WAIT') {
                    $podata->status = 'MAP ใบขน';
                }
                $podata->status_trans = 'Yes';

                $podata->update();
            }

        }
        return redirect('po-datas')->with('flash_message', ' Map ใบขน');
    }

    public function AllProcessCf()
    {
        $podatas = PoData::whereIn('status', ['WAIT', 'MAP ใบขน'])->get();

        echo $podatas->count();

        foreach ($podatas as $podata) {

            //Map transistion paper
            $candf = 0;
            $sapdatacf = SapDataCf::where('billing_doc', $podata->billing_name)->first();

            if (!empty($sapdatacf)) {
                $candf = $sapdatacf->net_value;
            }

            if (!empty($candf)) {
                $podata->candf = $candf;
                if($podata->status == 'MAP ใบขน'){
                    $podata->status = 'MAP ใบขน / C & F';
                }elseif($podata->status == 'WAIT'){
                    $podata->status = 'MAP C & F';
                }
                $podata->status_cnf = 'Yes';
                
                $podata->update();
            }
        }
        return redirect('po-datas')->with('flash_message', ' Map ใบขน');
    }

    public function changestatus($id)
    {
        $podata = PoData::findOrFail($id);

        $status = '';
        if ($podata->print_status == '') {
            $podata->print_status = 'Uploaded';
            $status = 'Uploaded';
        } else {
            $podata->print_status = '';
        }
        //var_dump($logpreparem);
        $podata->update();
        
        return redirect(url()->previous())->with('flash_message', ' updated!');
    }

    public function changemainstatus($id,$status)
    {
        $podata = PoData::findOrFail($id);

        $podata->main_status = $status;
        //var_dump($logpreparem);
        $podata->update();

        // return redirect('freeze-ms?status='. $status, compact('freezem'));
        return redirect(url()->previous())->with('flash_message', ' updated!');
    }
}
