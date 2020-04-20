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
use App\Role;
use App\RoleUser;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BvsApi
{
    private $client;
    private $base_url;
    private $url;
    private $nb = 0;
    private $auth = 0;
    private $bill_url = 'ZFACREC?representation=ZFACREC.$query&orderBy=NUM';
    private $user_url = 'ZREAUS("bvs_id")?representation=ZREAUS.$details';

    public function __construct()
    {
        $this->base_url = env('BVS_URL');
        $this->url = $this->base_url . $this->bill_url;
        $lb = Bill::latest('id')->first();
        if ($lb) {
            $this->url = $this->url . '&key=gt.' . $lb->bvs_id;
        }
        $this->auth = env('BVS_USERNAME') . ':' . env('BVS_PASSWORD') . '@';
        $this->client = new Client([
            'auth' => [
                env('BVS_USERNAME'),
                env('BVS_PASSWORD')
            ],
        ]);
    }

    public function fetch_bills()
    {
        try {
            do {
                $url = $this->addAuth($this->url);
//                $url = 'http://admin:admin@10.10.200.26:8124/api1/x3/erp/BVSTEST/ZFACREC?representation=ZFACREC.$query';
//                $url = 'http://admin:admin@10.10.200.26:8124/api1/x3/erp/BVSTEST/ZREAUS("000008")?representation=ZREAUS.$details';
                $response = file_get_contents($url);
                $body = json_decode($response, true);
                if (isset($body['$resources']))
                    $this->proccess_bills($body['$resources']);
                $this->url = $body['$links']['$next']['$url'];

            } while (isset($body['$links']['$next']));

            Log::info("$this->nb invoices fetchs");
        } catch (Exception $exception) {
            dump($exception->getMessage());
            Log::warning("invoices fetchs failled error: " . $exception->getMessage());
            return ['number_fetch' => $this->nb, 'success' => false, 'msg' => "invoices fetchs failled"];
        }

        return ['number_fetch' => $this->nb, 'success' => true, 'msg' => "$this->nb invoices fetchs"];
    }

    private function addAuth($url)
    {
        $nurl = explode('://', $url);
        $nurl = implode('://' . $this->auth, $nurl);
        return str_replace('mobile', 'api', $nurl);
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
        //
//        dump($resource);
        $cn = isset($resource['BPINAM']) ? strtolower($resource['BPINAM']) : strtolower($resource['SALFCY_REF']['$description']);
        $csn = isset($resource['TSCCOD0_REF']['$description']) ? $resource['TSCCOD0_REF']['$description'] : $resource['SALFCY_REF']['$description'];
        $c = [
            'name' => $cn,
            'sale_network' => $csn,
        ];

        $co = Customer::whereName($cn)->first();
        if ($co == null) {
            $co = Customer::create($c);
        }
        // username not yet
        if (isset($resource['REP']) && !empty(trim($resource['REP']))) {

            $uo = User::whereBvsId($resource['REP'])->first();
            if ($uo == null) {
                $url = $this->base_url . $this->user_url;

                $url = str_replace('bvs_id', $resource['REP'], $url);
                $url = $this->addAuth($url);

                $response = file_get_contents($url);
                $body = json_decode($response, true);
//                dump($body);
                $al=strtolower(explode(" ", $resource['REP_REF']['$description'])[0]);
                if (isset($body['REPNUM_REF']['$description'])) {
                    $n = $body['REPNUM_REF']['$description'];
                    $l = empty(trim($body['LOGIN']))?$al:$body['LOGIN'];
                    $uid = $body['REPNUM'];
                } else {
                    $n = $resource['REP_REF']['$description'];
                    $l = $al;
                    $uid = $resource['REP'];
                }

                if (isset($body['REPNUM_REF'])) {
                    $u = [
                        'name' => $n,
                        'username' => $l,
                        'password' => $l,
                        'bvs_id' => $uid,
                    ];
                }
                $uo = User::create($u);
            }

            if (!CustomerUser::where('user_id', $uo->id)->where('customer_id', $co->id)->exists()) {
                CustomerUser::create([
                    'user_id' => $uo->id,
                    'customer_id' => $co->id
                ]);
            }
            $role = Role::whereName('commercials')->first();
            if ($role == null)
                $role = Role::create([
                    'name' => 'commercials',
                    'display_name' => 'BVS Commercials',
                    'description' => 'this user can connect to comrec app']);
            if (!RoleUser::whereUserId($uo->id)->whereRoleId($role->id)->exists())
                RoleUser::create(['user_id'=>$uo->id,'role_id'=>$role->id,'user_type'=>'App\\User']);

        }


        // ACCDAT but for now INVDAT
        $a = isset($resource['SOLDE']) ? $resource['SOLDE'] : $resource['AMTATI'];
        $b = [
            'amount' => $a,
            'status' => 'new',
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