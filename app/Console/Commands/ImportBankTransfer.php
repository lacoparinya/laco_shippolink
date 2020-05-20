<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BankTransM;
use App\BankTransD;
use App\PoData;
use Illuminate\Support\Facades\Storage;

class ImportBankTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:importbanktransfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import File from /storage/app/public/entryform to ShipPoLink System';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = 'storage/app/public/banks/queues';
        $completedirectory = 'storage/app/public/banks/completes';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        foreach ($scanned_directory as $fileupload) {
            //Check Format file.
            $filenamearr = explode("_", $fileupload);
            
            if(sizeof($filenamearr) == 3){
                //Summary format

                

                $totalnoarr = explode(".", $filenamearr[2]);
                //var_dump(floatval($filenamearr[0] . "." . $filenamearr[1]));
                //echo $totalnoarr[0] . "." . $totalnoarr[1];
                if((sizeof($totalnoarr) == 3) && (floatval($totalnoarr[0].".". $totalnoarr[1]))){
                    //split INV or SO
                    $invandsoarr = explode("-", $filenamearr[1]);

                    $baseInv = "1291";
                    $middleInv = "00";

                    $baseSo = "1212";
                    $middleSo = "00";
                    
                    $tmpmain = array();
                    $tmpInv = array();

                    
                    foreach ($invandsoarr as $invandso) {
                        //check type INV or SO
                        $pos = strpos($invandso, "(");
                        echo $invandso."\n";
                        if ($pos === false) {
                            //check type INV 100%
                            $invno = $baseInv.$middleInv.substr($invandso,-4);
                            
                            $podata = PoData::where('inv_name',$invno)->first();

                            echo $invno . "\n";
                            $subtmpInv = array();
                            var_dump($podata);
                            if(!empty($podata)){
                                $subtmpInv['po_data_id'] = $podata->id;
                                $subtmpInv['income_usd'] = $podata->candf;
                                $tmpInv[] = $subtmpInv;
                            }
                            
                        }else{
                            $invandso = str_replace(")", "", str_replace("(","", $invandso));

                            echo $invandso . "\n";

                            $pos = strpos($invandso, "F");
                            //INV Partial recv
                            if($pos > 0){

                                $substrinv = explode("F", $invandso);
                                $rate = $substrinv[0];
                                $invno = $baseInv . $middleInv . substr($substrinv[1], -4);


                                $podata = PoData::where('inv_name', $invno)->first();

                                echo $invno . "\n";
                                $subtmpInv = array();
                                //var_dump($podata);
                                if (!empty($podata)) {
                                    $subtmpInv['po_data_id'] = $podata->id;
                                    $subtmpInv['income_usd'] = $podata->candf * $rate / 100;
                                    $tmpInv[] = $subtmpInv;
                                }

                            }else{
                                $pos = strpos($invandso, "B");
            
                                if ($pos > 0) {

                                    $substrinv = explode("B", $invandso);
                                    $rate = $substrinv[0];
                                    $saleno = $baseSo . $middleSo . substr($substrinv[1], -4);
                                    
                                    $podata = PoData::where('sale_order_name', $saleno)->first();

                                    echo $invno . "\n";
                                    $subtmpInv = array();
                                    var_dump($podata);
                                    if (!empty($podata)) {
                                        $subtmpInv['po_data_id'] = $podata->id;
                                        $subtmpInv['income_usd'] = $podata->candf*$rate/100;
                                        $tmpInv[] = $subtmpInv;
                                    }

                                } else {
                                    $invno = $baseInv . $middleInv . substr($invandso, -4);
                                }
                            }


                            $invno = $baseInv . $middleInv . substr($invandso, -4);
                        }


                    }
                    $subtmpInvB = array();
                    $subtmpInvB['other_case'] = 'ค่าจัดการBank';
                    $tmpInv[] = $subtmpInvB;

                    if (!is_dir('storage/app/public/banktransfer/' . date('Ymd'))) {
                        mkdir('storage/app/public/banktransfer/' . date('Ymd'), 0777, true);
                    }

                    if (copy($directory . '/' . $fileupload, 'storage/app/public/banktransfer/' . date('Ymd') . '/' . $fileupload)) {

                        $tmpmain['filename'] = str_replace(" ", "_", $fileupload);
                        $tmpmain['serverpath'] = 'storage/banktransfer/' . date('Ymd') . '/' . str_replace(" ", "_", $fileupload);
                        $tmpmain['processpath'] = 'storage/banktransfer/' . date('Ymd') . '/process_' . str_replace(" ", "_", $fileupload);
                        $tmpmain['type'] = 'PDF';
                        $tmpmain['trans_date'] = date('Y-m-d');
                        $tmpmain['total_usd'] = floatval($totalnoarr[0] . "." . $totalnoarr[1]);

                        $btm = BankTransM::create($tmpmain);

                        foreach ($tmpInv as $invobj) {
                            $invobj['bank_trans_m_id'] = $btm->id;
                            var_dump($invobj);
                            BankTransD::create($invobj);
                            if(!empty($invobj['po_data_id'])){
                                $this->_checkInvComplete($invobj['po_data_id']);
                            }
                            
                        }

                        $this->_reformatpdf($fileupload);

                        if (copy($directory . '/' . $fileupload, $completedirectory . '/' . $fileupload)) {
                            unlink($directory . '/' . $fileupload);
                        }

                    }

                }
            }
        }
    }

    private function _checkInvComplete($po_data_id)
    {
        $podata = PoData::findOrFail($po_data_id);
        $flag = false;
        if ($podata->banktransd->count() > 0) {
            $totalUsd = 0;
            foreach ($podata->banktransd as $banktransdObj) {
                $totalUsd += $banktransdObj->income_usd;
            }
            if (($podata->candf - $totalUsd) > 0) {
                $podata->status_transclose = 'No';
                $podata->update();
            } else {
                $podata->status_transclose = 'Yes';
                $podata->update();
            }
        }
    }

    private function _reformatpdf($filename)
    {

        //The PDF version that you want to convert
        //the file into.
        $pdfVersion = "1.4";

        //The path that you want to save the new
        //file to
        $newFiletmp = 'storage/app/public/banktransfer/' . date('Ymd') . '/' . 'process_' . $filename;


        $newFile = Storage::disk('public')->path($newFiletmp);

        //The path of the file that you want
        //to convert
        $currentFileTmp = 'storage/app/public/banktransfer/' . date('Ymd') . '/' . $filename;

        $currentFile = Storage::disk('public')->path($currentFileTmp);

        $gsPath = '"c:\\Program Files\\gs\\gs9.52\\bin\\gswin64c.exe" ';

        //Create the GhostScript command
        $gsCmd = $gsPath . " -sDEVICE=pdfwrite -dCompatibilityLevel=$pdfVersion -dNOPAUSE -dBATCH -sOutputFile=$newFiletmp $currentFileTmp > D:\\output.txt";
        echo $gsCmd;
        //Run it using PHP's exec function.
        exec($gsCmd);

        return 'process_' . $filename;
    }
}
