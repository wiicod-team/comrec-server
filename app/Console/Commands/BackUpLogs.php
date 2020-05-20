<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackUpLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'BackUpLogs';

    protected $signature = 'backup:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Back up logs to Amazon S3';


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
        //
        if (!App::isLocal()) {
            $localDisk = Storage::disk('log');
            $localFiles = $localDisk->allFiles();
            $cloudDisk = Storage::disk('s3Backup');
            $dat = Carbon::today()->format("Y-m-d");
            $pathPrefix = 'coLogs' . DIRECTORY_SEPARATOR . $dat.DIRECTORY_SEPARATOR;
            foreach ($localFiles as $file) {
                $contents = $localDisk->get($file);
                $cloudLocation = $pathPrefix . $file;
                $cloudDisk->put($cloudLocation, $contents);
//                $localDisk->delete($file);
            }
            Log::info("BackUpLogs of $dat done");
        }
        else {
            Log::info('BackUpLogs not backing up in local env');
        }
    }
}
