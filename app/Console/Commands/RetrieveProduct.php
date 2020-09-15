<?php

namespace App\Console\Commands;

use App\Helpers\BvsApi;
use Illuminate\Console\Command;

class RetrieveProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:product';

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
        $bvsApi->fetch_products();
    }
}
