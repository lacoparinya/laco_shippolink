<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\PoData;
use App\ShipData;
use Illuminate\Http\Request;

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

        if (!empty($keyword)) {
            $podatas = PoData::latest()->paginate($perPage);
        } else {
            $podatas = PoData::latest()->paginate($perPage);
        }

        return view('po-datas.index', compact('podatas'));
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
        $podatas = PoData::where('status','WAIT')->get();

        foreach ($podatas as $podata) {

            //Map transistion paper
            $transname = "";
            foreach ($podata->podatadetails as $podatadetail) {
                $shipData = ShipData::where('qty', $podatadetail->qty)
                    ->where('inv_no', trim($podata->inv_name))->first();

                if (!empty($shipData)) {
                    $transname = $shipData->trans_no;
                }
            }

            if (!empty($transname)) {
                $podata->trans_name = $transname;
                $podata->status = 'MAP Trans';
                $podata->update();
            }

        }
        return redirect('po-datas')->with('flash_message', ' Map ใบขน');
    }
}
