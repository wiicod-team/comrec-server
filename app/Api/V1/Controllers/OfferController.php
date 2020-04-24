<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\OfferRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Offer;

/**
 * @group Auth
 * Offer help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Offer resource
 * Class OfferController
 * @package App\Api\V1\Controllers
 */
class OfferController extends Controller
{

    public function index(){
        return RestHelper::get(Offer::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OfferRequest $request)
    {
        return RestHelper::store(Offer::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Offer::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OfferRequest $request,$id)
    {
        return RestHelper::update(Offer::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Offer::class,$id);
    }

}
