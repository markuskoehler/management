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
        // measure time
        $start = microtime(true);

        $dir = 'dump/'; // root is ''
        $backups = [];
        $file_count = 0;

        foreach (Storage::disk('storagebox')->allFiles('/' . $dir) as $file) {
            if(strpos($file, '.log') === false) {
                // get size
                //$size = Storage::disk('storagebox')->size($file) / 1024 / 1024;
                $original_file = $file;
                // remove dir and common prefix
                $file = str_replace($dir . 'vzdump', '', $file);
                // replace dashes and underscores
                $file = str_replace(['-', '_'], ' ', $file);
                // parse ending
                $file_parts = explode('.', ltrim($file));
                $name = $file_parts[0];
                $ending = $file_parts[1] . '.' . $file_parts[2];
                // split name parts
                $name_parts = explode(' ', $name);
                $type = $name_parts[0];
                $vmid = $name_parts[1];
                $date = $name_parts[2] . '-' . $name_parts[3] . '-' . $name_parts[4];
                $time = $name_parts[5] . ':' . $name_parts[6] . ':' . $name_parts[7];

                $backups[$vmid][$date] = $original_file;

                //$this->info($ending . ' ' . $type . ' ' . $vmid . ' ' . $date . ' ' . $time);

                $file_count++;
            }
            // todo get size, order by size, index by date, check consistency, delete by size/date/broken
        }
        dd($backups, $file_count);

        // output measured time
        $time_elapsed_secs = microtime(true) - $start;
        $this->info('Execution time: ' . round($time_elapsed_secs, 3) . ' sec');
    }
}
