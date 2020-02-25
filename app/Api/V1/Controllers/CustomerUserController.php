<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CustomerUserRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\CustomerUser;

/**
 * @group Auth
 * CustomerUser help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to CustomerUser resource
 * Class CustomerUserController
 * @package App\Api\V1\Controllers
 */
class CustomerUserController extends Controller
{

    public function index(){
        return RestHelper::get(CustomerUser::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerUserRequest $request)
    {
        return RestHelper::store(CustomerUser::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(CustomerUser::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomerUserRequest $request,$id)
    {
        return RestHelper::update(CustomerUser::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(CustomerUser::class,$id);
    }

}
