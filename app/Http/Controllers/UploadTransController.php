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
        $polistlist = PoData::where('status_transclose','!=','\'Yes\'')->orWhere('status_transclose')->get();
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
        $tmp['trans_date'] = $request->input('trans_date');
        $tmp['total_usd'] = $request->input('total_usd');
        //$tmp['total_bht'] = $request->input('total_bht');
        //$tmp['exchange_rate'] = $tmp['total_bht']/ $tmp['total_usd'];
        $tmp['note'] = $request->input('note');

        $btm = BankTransM::create($tmp);

        $btds = $request->input('custom-headers');
        foreach ($btds as $key => $value) {
            $tmpd = array();
            $tmpd['bank_trans_m_id'] = $btm->id;
            $tmpd['po_data_id'] = $value;

            BankTransD::create($tmpd);
            
            $this->_checkInvComplete($value);
        }

        $tmpd = array();
        $tmpd['bank_trans_m_id'] = $btm->id;
        $tmpd['other_case'] = 'ค่าจัดการBank';
        BankTransD::create($tmpd);

        return redirect('uploadtrans/index')->with('flash_message', ' Uploaded!');

    }

    public function index(Request $request){
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $banktransms = BankTransM::orderBy('trans_date','DESC')->paginate($perPage);
        } else {
            $banktransms = BankTransM::orderBy('trans_date', 'DESC')->paginate($perPage);
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

        $banktransm->trans_date = $requestData['trans_date'];
        $banktransm->total_usd = $requestData['total_usd'];
        $banktransm->note = $requestData['note'];

        $banktransm->update();

        foreach ($banktransm->banktransd()->get() as $banktransdObj) {
            $banktransd = BankTransD::findOrFail($banktransdObj->id);
            $banktransd->income_usd = $requestData['income_usd-'. $banktransdObj->id];
            $banktransd->update();

            if(isset($banktransdObj->po_data_id)){
                $this->_checkInvComplete($banktransdObj->po_data_id);
            }
            
        }


        return redirect('uploadtrans/index')->with('flash_message', ' EDit!');
    }

    public function testpdf(){
        $pdf = new \setasign\Fpdi\Fpdi();

        
        //$pdf->Output();

        $pdf->AddPage();
        $pdf->setSourceFile('C:\\Users\\parinya.k\\Desktop\\shippolink_test\\test1.pdf');

        // We import only page 1
        $tpl = $pdf->importPage(1);

        // Let's use it as a template from top-left corner to full width and height
        $pdf->useTemplate($tpl, 0, 0, null, null);

        // Set font and color
        $pdf->SetFont('Helvetica', 'B', 20); // Font Name, Font Style (eg. 'B' for Bold), Font Size
        $pdf->SetTextColor(0, 0, 0); // RGB

        // Position our "cursor" to left edge and in the middle in vertical position minus 1/2 of the font size
        $pdf->SetXY(0, 139.7 - 10);

        // Add text cell that has full page width and height of our font
        $pdf->Cell(215.9, 20, 'This text goes to middle', 0, 2, 'C');

        // Output our new pdf into a file
        // F = Write local file
        // I = Send to standard output (browser)
        // D = Download file
        // S = Return PDF as a string
        $pdf->Output('/tmp/new-file.pdf', 'F');
    }

    private function _checkInvComplete($po_data_id){
        $podata = PoData::findOrFail($po_data_id);
        $flag = false;
        if($podata->banktransd->count() > 0){
            $totalUsd = 0;
            foreach ($podata->banktransd as $banktransdObj) {
                $totalUsd += $banktransdObj->income_usd;
            }
            if(($podata->candf - $totalUsd) > 0){
                $podata->status_transclose = 'No';
                $podata->update();
            }else{
                $podata->status_transclose = 'Yes';
                $podata->update();
            }

        }
    }

    public function addnewinv($bank_trans_m_id){
        $banktransm = BankTransM::findOrFail($bank_trans_m_id);
        $polistlist = PoData::where('status_transclose', '!=', '\'Yes\'')->orWhere('status_transclose')->get();
        return view('uploadtrans.addnewinv', compact('polistlist', 'banktransm'));
    }

    public function addnewinvAction(Request $request, $bank_trans_m_id){
        $btds = $request->input('custom-headers');
        foreach ($btds as $key => $value) {
            $chk = BankTransD::where('bank_trans_m_id', $bank_trans_m_id)->where('po_data_id',$value)->first();

            if(empty($chk)){
                $tmpd = array();
                $tmpd['bank_trans_m_id'] =  $bank_trans_m_id;
                $tmpd['po_data_id'] = $value;

                BankTransD::create($tmpd); 
            }
            $this->_checkInvComplete($value);
        }

        return redirect('uploadtrans/view/' . $bank_trans_m_id)->with('flash_message', ' Add new inv!');
    }

    public function removeinv($bank_trans_d_id){

        $banktransd = BankTransD::findOrFail($bank_trans_d_id);

        $podataid =  $banktransd->po_data_id;

        $bank_trans_m_id = $banktransd->bank_trans_m_id;

        BankTransD::destroy($bank_trans_d_id);

        $this->_checkInvComplete($podataid);

        return redirect('uploadtrans/view/'. $bank_trans_m_id)->with('flash_message', ' deleted!');
    }
}
