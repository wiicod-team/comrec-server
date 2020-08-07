<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\DeliveryRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Delivery;
use App\Invoice;
use Foris\MoMoSdk\Collection;
use Foris\OmSdk\OmSdk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @group Auth
 * Delivery help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Delivery resource
 * Class DeliveryController
 * @package App\Api\V1\Controllers
 */
class PaymentController extends Controller
{

    /* Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function buy(Request $request)
    {
        //
//        return RestHelper::store(Ticket::class,$request->all());
        $rule = [
            'user_id' => 'integer|exists:users,id',
            'invoice_id' => 'required|integer|exists:invoices,id',
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 422);

        } else {
            $i = Invoice::find($request->invoice_id);
            if ($i->status == "paid")
                return Response::json(["msg" => "invoice already paid"], 201);
            if ($i->payment_method == 'momo') {
                $momo = new Collection();
                $res = $momo->requestToPay($i->amount, $i->payment_number);
                Log::warning("Momo Request ".json_encode($res));
                if ($res["success"]) {
                    $i->payment_id = $res["externalId"];
                    $i->payment_responds = $res;
                    $i->status = "pending";
                    $i->save();
                } else {
                    $msg = [
                        "unpaid" => "could not proceed to payment",
                    ];
                    return Response::json($msg, 422, [], JSON_NUMERIC_CHECK);
                }
            } elseif ($i->payment_method == 'om') {
                $om = new OmSdk();
                $res = $om->webPayment([
                    "amount" => $i->amount,
                    "currency"=>env("OM_CURRENCY"),
                    "order_id" => env("OM_ORDER_PREFIX") . $i->id
                ]);
                Log::warning("Momo Request ".json_encode($res));
                $i->status = "pending";
                $i->payment_id = $res["notif_token"];
                $i->payment_responds = [
                    'notif_token' => $res["notif_token"],
                    'pay_token' => $res["pay_token"],
                ];
                $i->save();
            }
            return Response::json($res, 200, [], JSON_NUMERIC_CHECK);
        }

    }

    public function callback($method, Request $request)
    {

        Log::info('api get callback with' . json_encode($request->all()));
        if ($method == 'om') {
            $i = Invoice::wherePaymentId($request->notif_token)->first();
            if ($i) {
                if (strtolower($request->status) == "success") {
                    $i->status = 'paid';
                    $i->save();
                    return Response::json($request->all(), 200, [], JSON_NUMERIC_CHECK);
                }
            }
        } elseif ($method == 'momo') {

        }


    }

    public function check($id, Request $request)
    {

        $i = Invoice::find($id);

        if ($i == null)
            return Response::json(["msg" => "the given id does not match any invoice"], 404, [], JSON_NUMERIC_CHECK);
        if ($i->status == 'paid')
            return Response::json($i, 200, [], JSON_NUMERIC_CHECK);

        if ($i->payment_method == 'om') {
            $om = new OmSdk();
            $res = $om->checkTransactionStatus(
                env("OM_ORDER_PREFIX") . $i->id,
                $i->amount,
                $i->payment_responds['paid_token']
            );
            if (strtolower($request->status) == "success") {
                $i->status = 'paid';
                $i->save();
                return Response::json($request->all(), 200, [], JSON_NUMERIC_CHECK);
            }

        } elseif ($i->payment_method == 'momo') {

            $momo = new Collection();
            $res = $momo->getTransaction($i->payment_id);
            Log::warning("transaction status".json_encode($res));
            if ($res['success'] && $res['status'] == 'SUCCESSFUL') {
                $i->status = 'paid';
                $i->save();
            }

        }
        return Response::json($i, 200, [], JSON_NUMERIC_CHECK);


    }


}
