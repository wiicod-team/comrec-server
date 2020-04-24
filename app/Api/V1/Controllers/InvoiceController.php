<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\InvoiceRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Invoice;

/**
 * @group Auth
 * Invoice help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Invoice resource
 * Class InvoiceController
 * @package App\Api\V1\Controllers
 */
class InvoiceController extends Controller
{

    public function index(){
        return RestHelper::get(Invoice::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(InvoiceRequest $request)
    {
        return RestHelper::store(Invoice::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Invoice::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InvoiceRequest $request,$id)
    {
        return RestHelper::update(Invoice::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Invoice::class,$id);
    }

}
