<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FileUpload;
use App\PoData;

class ImportExportEntryForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:importenrtyform';

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
        $directory = 'storage/app/public/entryforms/queues';
        $completedirectory = 'storage/app/public/entryforms/completes';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        foreach ($scanned_directory as $fileupload) {
            //check file name
            $trans = explode(".", $fileupload);
            if(sizeof($trans) == 2){
                
                if(!is_dir('storage/app/public/pdf/' . date('Ymd'))){
                    mkdir('storage/app/public/pdf/' . date('Ymd'), 0777, true);
                }

                echo 'storage/pdf/' . date('Ymd');
                if (copy($directory . '/' . $fileupload, 'storage/app/public/pdf/' . date('Ymd') . '/' . $fileupload)) {

                    //echo $fileupload . "\n";
                    $tmp['filename'] = $fileupload;
                    $tmp['serverpath'] = 'storage/pdf/' . date('Ymd') . '/' . $fileupload;
                    $tmp['type'] = 'PDF';
                    $tmp['invno'] = $trans[0];

                    $chekData = FileUpload::where('filename', $fileupload)->first();

                    $podata = PoData::where('inv_name', $trans[0])->first();

                    if (empty($podata)) {
                        $tmp['status'] = 'UPLOADED';
                    } else {
                        $tmp['po_data_id'] = $podata->id;
                        $tmp['status'] = 'MAPPED';
                    }
                    //echo $fileupload ;
                    if(empty($chekData)){
                        //echo " New \n";
                        FileUpload::create($tmp);
                    }else{
                        //echo " Update \n";
                        $chekData->update($tmp);
                    }

                    if (copy($directory . '/' . $fileupload, $completedirectory . '/' . $fileupload)) {
                        unlink($directory . '/' . $fileupload);
                    }
                }
            }
        }
    }
}
