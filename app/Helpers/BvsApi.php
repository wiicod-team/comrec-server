<?php
/**
 * Created by PhpStorm.
 * User: evari
 * Date: 3/31/2020
 * Time: 9:31 AM
 */

namespace App\Helpers;


use App\Bill;
use App\Customer;
use App\CustomerUser;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BvsApi
{
    private $client;
    private $url;
    private $nb = 0;
    private $auth = 0;

    public function __construct()
    {
        $this->url = env('BVS_URL');
        $this->auth=env('BVS_USERNAME').':'.env('BVS_PASSWORD').'@';
        $this->client = new Client([
            'auth' => [
                env('BVS_USERNAME'),
                env('BVS_PASSWORD')
            ],
        ]);
    }

    public function fetch_bills()
    {
        try{
            do {
                $url = $this->addAuth($this->url);
                $response = file_get_contents($url);
                $body = json_decode($response, true);
                if(isset($body['$resources']))
                    $this->proccess_bills($body['$resources']);
                $this->url = $body['$links']['$next']['$url'];

            } while (isset($body['$links']['$next']));

            Log::info("$this->nb invoices fetchs");
        }catch (Exception $exception){
//            dump($exception->getMessage());
            Log::warning("invoices fetchs failled");
            return ['number_fetch'=>$this->nb,'success'=>false,'msg'=>"invoices fetchs failled"];
        }

        return ['number_fetch'=>$this->nb,'success'=>true,'msg'=>"$this->nb invoices fetchs"];
    }

    private function addAuth($url)
    {
        return  str_replace('mobile','api',implode('://'.$this->auth,explode('://',$url)));
    }

    private function proccess_bills($resources)
    {
        foreach ($resources as $resource) {
            $this->proccess_bill($resource);
            $this->nb++;
        }
    }

    private function proccess_bill($resource)
    {
        //sale network not yet
        $cn = isset($resource['BPINAM']) ? strtolower($resource['BPINAM']) :strtolower($resource['SALFCY_REF']['$description']);
        $c = [
            'name' => $cn,
            'sale_network' => $resource['SALFCY_REF']['$description'],
        ];
        $co = Customer::whereName($cn)->first();
        if ($co == null) {
            $co = Customer::create($c);
        }
        // username not yet
        if(isset( $resource['REP_REF'])){
            $us = strtolower(explode(" ", $resource['REP_REF']['$description'])[0]);
            $u = [
                'name' => $resource['REP_REF']['$description'],
                'username' => $us,
                'password' => bcrypt($us),
                'bvs_id' => $resource['REP'],
            ];
            $uo = User::whereBvsId($resource['REP'])->first();
            if ($uo == null) {
                $uo = User::create($u);
            }
            if (!CustomerUser::where('user_id', $uo->id)->where('customer_id', $co->id)->exists()) {
                CustomerUser::create([
                    'user_id'=>$uo->id,
                    'customer_id'=>$co->id
                ]);
            }
        }


        // ACCDAT but for now INVDAT
        $a= isset($resource['SOLDE'])?$resource['SOLDE']:$resource['AMTATI'];
        $b = [
            'amount' => $a,
            'status'=>'new',
            'bvs_id' => $resource['NUM'],
            'creation_date' => $resource['INVDAT'],
            'customer_id' => $co->id,
        ];

        $bo = Bill::whereBvsId($resource['NUM'])->first();
        if ($bo == null) {
            $bo = Bill::create($b);
        }
    }

}