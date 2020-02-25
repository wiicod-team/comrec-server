<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ReceiptRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Receipt;

/**
 * @group Auth
 * Receipt help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Receipt resource
 * Class ReceiptController
 * @package App\Api\V1\Controllers
 */
class ReceiptController extends Controller
{

    public function index(){
        return RestHelper::get(Receipt::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ReceiptRequest $request)
    {
        return RestHelper::store(Receipt::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Receipt::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ReceiptRequest $request,$id)
    {
        return RestHelper::update(Receipt::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Receipt::class,$id);
    }

}
