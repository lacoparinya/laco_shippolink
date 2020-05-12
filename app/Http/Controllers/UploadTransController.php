<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\BankTransM;
use App\BankTransD;
use App\PoData;
use Bnb\PdfToImage;

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
        $tmp['processpath'] = 'storage/banktransfer/' . date('Ymd') . '/process_' . $filenamereal;
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

        $this->reformatpdf($filenamereal);

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

        $pathToPdf  = "C:\\Users\\parinya.k\\Desktop\\NIPPON2.pdf";
        $pathToWhereImageShouldBeStored = "C:\\Users\\parinya.k\\Desktop\\";

        //$pdf = new \FDPI();
        $pdf = new \setasign\Fpdi\Fpdi();
       

        $pdf->AddPage();
        $pdf->setSourceFile($pathToPdf);

        // We import only page 1
        $tpl = $pdf->importPage(1);

        // Let's use it as a template from top-left corner to full width and height
        $pdf->useTemplate($tpl, 0, 0, null, null);

        // Set font and color
        $pdf->SetFont('Helvetica', 'B', 8); // Font Name, Font Style (eg. 'B' for Bold), Font Size
        $pdf->SetTextColor(0, 0, 0); // RGB

        // Position our "cursor" to left edge and in the middle in vertical position minus 1/2 of the font size
        $pdf->SetXY(0, 139.7 - 10);

        // Add text cell that has full page width and height of our font
        $pdf->Cell(300, 10, 'INV. 1291013899  USD  31,686.10', 0, 2, 'C');
        $pdf->Cell(300, 10, 'INV. 1291013881  USD  17,858.75', 0, 2, 'C');
        $pdf->Cell(300, 10, 'INV. 1291013871  USD  19,709.38', 0, 2, 'C');
        $pdf->Cell(300, 10, 'INV. 1291013915  USD  25,606.02', 0, 2, 'C');
        $pdf->Cell(300, 10, 'INV. 1291013916  USD  27,141.50', 0, 2, 'C');
        $pdf->Cell(300, 10, 'INV. 1291013917  USD  31,666.98', 0, 2, 'C');

        $pdf->Output('new-file.pdf', 'D');
        
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

    public function processPdf($id){
        $banktransm = BankTransM::findOrFail($id);

        $pathToPdf  = $banktransm->processpath;
        $pathToWhereImageShouldBeStored = "C:\\Users\\parinya.k\\Desktop\\";
        //echo $pathToPdf;
        //$pdf = new \FDPI();
        $pdf = new \setasign\Fpdi\Fpdi();


        $pdf->AddPage();
        $pdf->setSourceFile($pathToPdf);

        // We import only page 1
        $tpl = $pdf->importPage(1);

        // Let's use it as a template from top-left corner to full width and height
        $pdf->useTemplate($tpl, 0, 0, null, null);

        // Set font and color
        $pdf->SetFont('Helvetica', 'B', 8); // Font Name, Font Style (eg. 'B' for Bold), Font Size
        $pdf->SetTextColor(0, 0, 0); // RGB

        // Position our "cursor" to left edge and in the middle in vertical position minus 1/2 of the font size
        $pdf->SetXY(0, 139.7 - 10);

        // Add text cell that has full page width and height of our font
        foreach ($banktransm->banktransd()->get() as $item2) {
            if(!empty($item2->podata->inv_name)){
                $pdf->Cell(320, 5, 'INV. ' . $item2->podata->inv_name .'  USD  '. $item2->income_usd, 0, 2, 'C');
            }
        }
        $pdf->Output('invadd_'.$banktransm->filename, 'D');
    }

    private function reformatpdf($filename){

        //The PDF version that you want to convert
        //the file into.
        $pdfVersion = "1.4";

        //The path that you want to save the new
        //file to
        $newFiletmp = 'storage/banktransfer/' . date('Ymd') . '/'. 'process_' . $filename;


        $newFile = Storage::disk('public')->path($newFiletmp);

        //The path of the file that you want
        //to convert
        $currentFileTmp = 'storage/banktransfer/' . date('Ymd') . '/' . $filename;

        $currentFile = Storage::disk('public')->path($currentFileTmp);

        $gsPath = '"c:\\Program Files\\gs\\gs9.52\\bin\\gswin64c.exe" ';

        //Create the GhostScript command
        $gsCmd = $gsPath." -sDEVICE=pdfwrite -dCompatibilityLevel=$pdfVersion -dNOPAUSE -dBATCH -sOutputFile=$newFiletmp $currentFileTmp > D:\\output.txt";
        echo $gsCmd ;
        //Run it using PHP's exec function.
        exec($gsCmd);

        return 'process_'. $filename;

    }

    public function destroy($id)
    {
        BankTransM::destroy($id);

        return redirect('uploadtrans/index')->with('flash_message', ' deleted!');
    }
}
