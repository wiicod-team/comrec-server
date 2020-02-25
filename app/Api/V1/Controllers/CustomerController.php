<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CustomerRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Customer;

/**
 * @group Auth
 * Customer help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Customer resource
 * Class CustomerController
 * @package App\Api\V1\Controllers
 */
class CustomerController extends Controller
{

    public function index(){
        return RestHelper::get(Customer::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerRequest $request)
    {
        return RestHelper::store(Customer::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Customer::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomerRequest $request,$id)
    {
        return RestHelper::update(Customer::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Customer::class,$id);
    }

}
