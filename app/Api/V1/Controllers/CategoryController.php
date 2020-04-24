<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CategoryRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Category;

/**
 * @group Auth
 * Category help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Category resource
 * Class CategoryController
 * @package App\Api\V1\Controllers
 */
class CategoryController extends Controller
{

    public function index(){
        return RestHelper::get(Category::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        return RestHelper::store(Category::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Category::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request,$id)
    {
        return RestHelper::update(Category::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Category::class,$id);
    }

}
