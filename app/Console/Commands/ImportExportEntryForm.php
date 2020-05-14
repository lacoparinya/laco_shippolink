<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        print_r($scanned_directory);
    }
}
