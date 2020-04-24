<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\InvoiceItemRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\InvoiceItem;

/**
 * @group Auth
 * InvoiceItem help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to InvoiceItem resource
 * Class InvoiceItemController
 * @package App\Api\V1\Controllers
 */
class InvoiceItemController extends Controller
{

    public function index(){
        return RestHelper::get(InvoiceItem::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(InvoiceItemRequest $request)
    {
        return RestHelper::store(InvoiceItem::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(InvoiceItem::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InvoiceItemRequest $request,$id)
    {
        return RestHelper::update(InvoiceItem::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(InvoiceItem::class,$id);
    }

}
