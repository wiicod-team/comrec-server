<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\DeliveryRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Delivery;

/**
 * @group Auth
 * Delivery help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Delivery resource
 * Class DeliveryController
 * @package App\Api\V1\Controllers
 */
class DeliveryController extends Controller
{

    public function index(){
        return RestHelper::get(Delivery::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DeliveryRequest $request)
    {
        return RestHelper::store(Delivery::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Delivery::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DeliveryRequest $request,$id)
    {
        return RestHelper::update(Delivery::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Delivery::class,$id);
    }

}
