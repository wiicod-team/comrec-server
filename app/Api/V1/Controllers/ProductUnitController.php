<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ProductUnitRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\ProductUnit;

/**
 * @group Auth
 * ProductUnit help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to ProductUnit resource
 * Class ProductUnitController
 * @package App\Api\V1\Controllers
 */
class ProductUnitController extends Controller
{

    public function index(){
        return RestHelper::get(ProductUnit::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductUnitRequest $request)
    {
        return RestHelper::store(ProductUnit::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(ProductUnit::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductUnitRequest $request,$id)
    {
        return RestHelper::update(ProductUnit::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(ProductUnit::class,$id);
    }

}
