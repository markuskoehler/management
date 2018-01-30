<?php

namespace App\Console\Commands;

use App\Models\InternalReceipt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SpacesMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spaces:maintenance {--o|orphaned : Detect orphaned files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maintain the DigitalOcean Spaces Storage';

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
        $orphanedFiles = $this->option('orphaned');

        if($orphanedFiles) {
            $this->info('Start cleaning up orphaned files');

            $files = Storage::cloud()->files('management/internalreceipts');
            $all = count($files);
            $unsigned_raw = InternalReceipt::whereNotNull('unsigned_document')->select('unsigned_document')->get();
            $signed_raw = InternalReceipt::whereNotNull('signed_document')->select('signed_document')->get();
            $unsigned = [];
            $signed = [];
            foreach($unsigned_raw as $item) {
                $unsigned[] = $item->unsigned_document;
            }
            foreach($signed_raw as $item) {
                $signed[] = $item->signed_document;
            }
            $unused = array_diff($files, $unsigned, $signed);
            $this->info('Will clean up ' . count($unused) . ' unused files from total ' . $all);
            Storage::cloud()->delete($unused);
            $this->info('Cleanup complete');
            // todo add time measurement
        }

        //
    }
}
