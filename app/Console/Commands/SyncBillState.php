<?php

namespace App\Console\Commands;

use App\Helpers\BvsApi;
use Illuminate\Console\Command;

class SyncBillState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $bvsApi = new BvsApi();
        $bvsApi->fetch_sync_bills();
    }
}
