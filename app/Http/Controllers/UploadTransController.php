<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\BankTransM;
use App\BankTransD;
use App\PoData;

class UploadTransController extends Controller
{
    public function create(){
        $polistlist = PoData::pluck('inv_name', 'id');
        return view('uploadtrans.create', compact('polistlist'));
    }

    public function createAction(Request $request){

        $filenamereal = $request->file('uploadfile' )->getClientOriginalName();

        $uploadfilepath = $request->file('uploadfile')->storeAs('public/banktransfer/' . date('Ymd') . '/', $filenamereal);

        $trans = explode(".", $filenamereal);
        $tmp = array();

        $tmp['filename'] = $filenamereal;
        $tmp['serverpath'] = 'storage/banktransfer/' . date('Ymd') . '/' . $filenamereal;
        $tmp['type'] = 'PDF';
        $tmp['total_usd'] = $request->input('total_usd');
        $tmp['total_bht'] = $request->input('total_bht');
        $tmp['exchange_rate'] = $tmp['total_bht']/ $tmp['total_usd'];
        $tmp['note'] = $request->input('note');

        $btm = BankTransM::create($tmp);

        $btds = $request->input('custom-headers');
        foreach ($btds as $key => $value) {
            $tmpd = array();
            $tmpd['bank_trans_m_id'] = $btm->id;
            $tmpd['po_data_id'] = $value;

            BankTransD::create($tmpd);
        }

        return redirect('uploadtrans/index')->with('flash_message', ' Uploaded!');

    }

    public function index(Request $request){
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $banktransms = BankTransM::orderBy('created_at')->paginate($perPage);
        } else {
            $banktransms = BankTransM::orderBy('created_at')->paginate($perPage);
        }

        return view('uploadtrans.index', compact('banktransms'));
    }

    public function view($id){

        $banktransm = BankTransM::findOrFail($id);

        return view('uploadtrans.view', compact('banktransm'));
    }

    public function edit($id)
    {

        $banktransm = BankTransM::findOrFail($id);

        return view('uploadtrans.edit', compact('banktransm'));
    }

    public function editAction(Request $request, $id)
    {

        $requestData = $request->all();

        $banktransm = BankTransM::findOrFail($id);
        foreach ($banktransm->banktransd()->get() as $banktransdObj) {
            $banktransd = BankTransD::findOrFail($banktransdObj->id);
            $banktransd->income_usd = $requestData['income_usd-'. $banktransdObj->id];
            $banktransd->update();
        }


        return redirect('uploadtrans/index')->with('flash_message', ' EDit!');
    }
}
