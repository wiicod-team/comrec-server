<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ProductRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Product;

/**
 * @group Auth
 * Product help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Product resource
 * Class ProductController
 * @package App\Api\V1\Controllers
 */
class ProductController extends Controller
{

    public function index(){
        return RestHelper::get(Product::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        return RestHelper::store(Product::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Product::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request,$id)
    {
        return RestHelper::update(Product::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Product::class,$id);
    }

}
