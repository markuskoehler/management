<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupsMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backups:maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Organize the backups on Hetzner Storagebox';

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
        foreach (Storage::disk('storagebox')->allFiles('/dump/') as $file) {
            if(strpos($file, '.log') === false) $this->info($file);
            // todo get size, order by size, index by date, check consistency, delete by size/date/broken
        }
    }
}
