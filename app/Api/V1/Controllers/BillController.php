<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\BillRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Bill;

/**
 * @group Auth
 * Bill help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Bill resource
 * Class BillController
 * @package App\Api\V1\Controllers
 */
class BillController extends Controller
{

    public function index(){
        return RestHelper::get(Bill::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BillRequest $request)
    {
        return RestHelper::store(Bill::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Bill::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BillRequest $request,$id)
    {
        return RestHelper::update(Bill::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Bill::class,$id);
    }

}
