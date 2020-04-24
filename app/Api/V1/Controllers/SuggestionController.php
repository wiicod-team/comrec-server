<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\SuggestionRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\Suggestion;

/**
 * @group Auth
 * Suggestion help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Suggestion resource
 * Class SuggestionController
 * @package App\Api\V1\Controllers
 */
class SuggestionController extends Controller
{

    public function index(){
        return RestHelper::get(Suggestion::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SuggestionRequest $request)
    {
        return RestHelper::store(Suggestion::class, $request->all());
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return RestHelper::show(Suggestion::class,$id);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SuggestionRequest $request,$id)
    {
        return RestHelper::update(Suggestion::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return RestHelper::destroy(Suggestion::class,$id);
    }

}
