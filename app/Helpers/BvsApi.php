<?php
/**
 * Created by PhpStorm.
 * User: evari
 * Date: 3/31/2020
 * Time: 9:31 AM
 */

namespace App\Helpers;


use App\Bill;
use App\Category;
use App\Customer;
use App\CustomerUser;
use App\Product;
use App\ProductUnit;
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
    private $bill_url = 'ZFACREC?representation=ZFACREC.$query&orderBy=NUM&count=200';
    private $sync_bill_url = 'ZFACRECOU2?representation=ZFACRECOU2.$query&orderBy=NUM&count=200';
    private $product_url = 'ZSTKAPP?representation=ZSTKAPP.$query&orderBy=NUM';
    private $user_url = 'ZREAUS("bvs_id")?representation=ZREAUS.$details';

    public function __construct()
    {
        $this->base_url = env('BVS_URL');

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
        $this->url = $this->base_url . $this->bill_url;
       /* $lb = Bill::latest('id')->first();
        if ($lb) {
            $this->url = $this->url . '&key=gt.' . $lb->bvs_id;
        }*/
        try {
            do {
                $url = $this->addAuth($this->url);
//                $url = 'http://admin:admin@10.10.200.26:8124/api1/x3/erp/BVSTEST/ZFACREC?representation=ZFACREC.$query';
//                $url = 'http://admin:admin@10.10.200.26:8124/api1/x3/erp/BVSTEST/ZREAUS("000008")?representation=ZREAUS.$details';
                $ctx = stream_context_create(array('http'=>
                    array(
                        'timeout' => 240,  //1200 Seconds is 20 Minutes
                    )
                ));
                $response = file_get_contents($url,false,$ctx);
                $body = json_decode($response, true);
                if (isset($body['$resources']))
                    $this->proccess_bills($body['$resources']);
                if (isset($body['$links']['$next']))
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


    public function fetch_sync_bills()
    {
        $this->url = $this->base_url . $this->sync_bill_url;
        try {
            do {
                $url = $this->addAuth($this->url);
//                $url = 'http://admin:admin@10.10.200.26:8124/api1/x3/erp/BVSTEST/ZFACREC?representation=ZFACREC.$query';
//                $url = 'http://admin:admin@10.10.200.26:8124/api1/x3/erp/BVSTEST/ZREAUS("000008")?representation=ZREAUS.$details';
                $ctx = stream_context_create(array('http'=>
                    array(
                        'timeout' => 240,  //1200 Seconds is 20 Minutes
                    )
                ));
                $response = file_get_contents($url,false,$ctx);
                $body = json_decode($response, true);
                if (isset($body['$resources']))
                    $this->sync_bills($body['$resources']);
                if (isset($body['$links']['$next']))
                    $this->url = $body['$links']['$next']['$url'];

            } while (isset($body['$links']['$next']));

            Log::info("$this->nb invoices syncs");
        } catch (Exception $exception) {
            dump($exception->getMessage());
            Log::warning("invoices fetchs failled error: " . $exception->getMessage());
            return ['number_fetch' => $this->nb, 'success' => false, 'msg' => "invoices fetchs failled"];
        }

        return ['number_fetch' => $this->nb, 'success' => true, 'msg' => "$this->nb invoices fetchs"];

    }

    public function fetch_products()
    {
        $this->url = $this->base_url . $this->product_url;
        $lp = ProductUnit::latest('id')->first();
        if ($lp) {
            $this->url = $this->url . '&key=get.' . $lp->bvs_id;
        }
        try {
            do {
                $url = $this->addAuth($this->url);
                $ctx = stream_context_create(array('http'=>
                    array(
                        'timeout' => 240,  //1200 Seconds is 20 Minutes
                    )
                ));
                $response = file_get_contents($url,false,$ctx);
//                $response = file_get_contents($url);
                $body = json_decode($response, true);
                if (isset($body['$resources']))
                    $this->proccess_products($body['$resources']);
                if (isset($body['$links']['$next']))
                    $this->url = $body['$links']['$next']['$url'];

            } while (isset($body['$links']['$next']));

            Log::info("$this->nb products fetchs");
        } catch (Exception $exception) {
//            dump($exception->getMessage());
            Log::warning("products fetchs failled error: " . $exception->getMessage());
            return ['number_fetch' => $this->nb, 'success' => false, 'msg' => "products fetchs failled"];
        }

        return ['number_fetch' => $this->nb, 'success' => true, 'msg' => "$this->nb products fetchs"];
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

    private function sync_bills($resources)
    {
        foreach ($resources as $resource) {
            $this->sync_bill($resource);
            $this->nb++;
        }
    }

    private function proccess_products($resources)
    {
        foreach ($resources as $resource) {
            $this->proccess_product($resource);
            $this->nb++;
        }
    }

    private function proccess_bill($resource)
    {
        //
//        dump($resource);
        $cn = isset($resource['BPINAM']) ? $resource['BPINAM'] : $resource['SALFCY_REF']['$description'];
        $csn = isset($resource['TSCCOD0_REF']['$description']) ? $resource['TSCCOD0_REF']['$description'] : $resource['SALFCY_REF']['$description'];
        $c = [
            'name' => trim($cn),
            'sale_network' => trim($csn),
            'bvs_id'=>trim($resource['BPCINV'])

        ];

        $co = Customer::whereBvsId(trim($resource['BPCINV']))->first();
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
                $al =isset($resource['REP_REF']['$description'])?
                    strtolower(explode(" ", $resource['REP_REF']['$description'])[0])
                    : $body['$actxLogin'];
                if (isset($body['REPNUM_REF']['$description'])) {
                    $n = $body['REPNUM_REF']['$description'];
                    $l = empty(trim($body['LOGIN'])) ? $al : $body['LOGIN'];
                    $uid = $body['REPNUM'];
                } else {
                    $n = $al;
                    $l = strtolower(explode(" ", $al)[0]);
                    $uid = $resource['REP'];
                }

                $uo = User::whereUsername(trim($l))->first();
                if($uo==null){
                    if (isset($body['REPNUM_REF'])) {
                        $u = [
                            'name' => trim($n),
                            'username' => trim($l),
                            'password' => $l,
                            'bvs_id' => $uid,
                        ];
                    }
                    $uo = User::create($u);
                }

            }

            if (!CustomerUser::where('user_id', $uo->id)->where('customer_id', $co->id)->exists()) {
                CustomerUser::create([
                    'user_id' => $uo->id,
                    'customer_id' => $co->id
                ]);
            }
            $role = Role::whereName('comrec.user')->first();
            if ($role == null)
                $role = Role::create([
                    'name' => 'comrec.user',
                    'display_name' => 'BVS Comrec User',
                    'description' => 'this user can connect to comrec app']);
            if (!RoleUser::whereUserId($uo->id)->whereRoleId($role->id)->exists())
                RoleUser::create(['user_id' => $uo->id, 'role_id' => $role->id, 'user_type' => 'App\\User']);

        }


        // ACCDAT but for now INVDAT
        if (isset($resource['SOLDE'])) {
//            $a = isset($resource['SOLDE']) ? $resource['SOLDE'] : $resource['AMTATI'];
            $a = $resource['SOLDE'];
            $b = [
                'amount' => $a,
                'status' => 'pending',
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

    private function proccess_product($resource)
    {
        //
//        dump($resource);

        $c = [
            'name' => trim($resource['TEXTE']),
            'description' => trim($resource['TSICOD_1']),
        ];

        $co = Category::whereName($c['name'])->first();
        if ($co == null) {
            $co = Category::create($c);
        }

        if (isset($resource['ITMDES']) && !empty(trim($resource['STU']))) {

            $unit = trim($resource['STU']);
            $pn = trim($resource['ITMDES']);
            $pos = strrpos($pn, $unit);

            if ($pos !== false) {
                $pn = substr_replace($pn, '', $pos, strlen($unit));
            }
            $p = [
                'name' => $pn,
//                'description' => trim($resource['TSICOD_1']),
                'category_id' => $co->id

            ];
            $po = Product::whereName($p['name'])->first();
            if ($po == null) {
                $po = Product::create($p);
            }

            $pu = [
                'unit' => $unit,
                'quantity' => intval(trim($resource['SOLDE'])),
                'bvs_id' => trim($resource['NUM']),
                'amount' => trim($resource['PRI2']),
                'product_id' => $po->id

            ];

//            $puo = ProductUnit::whereBvsId($pu['bvs_id'])->first();
            $puo = ProductUnit::where(function ($query) use ($pu) {
                $query->whereUnit($pu['unit'])->whereProductId($pu['product_id']);
            })->orWhere(function ($query) use ($pu) {
                $query->where('bvs_id', $pu['bvs_id']);
            })->first();

            if ($puo == null) {
                $puo = ProductUnit::create($pu);
            } else {
                $puo->quantity += $pu['quantity'];
                $puo->bvs_id = $pu['bvs_id'];
                $puo->save();
            }

        }

    }

    private function sync_bill($resource)
    {
        if(isset($resource["NUM"])&& Bill::whereBvsId($resource["NUM"])->exists()){
            $b = Bill::whereBvsId($resource["NUM"])->first();
            $a= $resource["MONTANT1"]-$resource["MONTANT2"];
            $b->amount = $a;
            if($a==0){
                $b->status= 'paid';
            }elseif ($a<0){
                $b->status='remain';
            }
            $b->save();

        }
    }


}