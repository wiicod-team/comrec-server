<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\OfferProductUnitRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\OfferProductUnit;

/**
 * @group Auth
 * OfferProductUnit help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to OfferProductUnit resource
 * Class OfferProductUnitController
 * @package App\Api\V1\Controllers
 */
class OfferProductUnitController extends Controller
{

    public function index(){
        return RestHelper::get(OfferProductUnit::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OfferProductUnitRequest $request)
    {
        return RestHelper::store(OfferProductUnit::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(OfferProductUnit::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OfferProductUnitRequest $request,$id)
    {
        return RestHelper::update(OfferProductUnit::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(OfferProductUnit::class,$id);
    }

}
