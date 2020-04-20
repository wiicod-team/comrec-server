<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\PermissionRoleRequest;
use App\Helpers\RestHelper;
use App\Http\Controllers\Controller;
use App\PermissionRole;
use Illuminate\Support\Facades\Response;

/**
 * @group Auth
 * Role help define privilege to allow user to have certain control over certain specific actions
 * This class is intended to manage all action related to Role resource
 * Class RoleController
 * @package App\Api\V1\Controllers
 */
class PermissionRoleController extends Controller
{

    public function index(){
        return RestHelper::get(PermissionRole::class);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param PermissionRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PermissionRoleRequest $request)
    {
        $this->validate(
            $request, [
                'permission_id'=>'required|integer|exists:permissions,id',
                'role_id'=>'required|integer|exists:roles,id'
            ]
        );

        $permission_role = new PermissionRole;
        $permission_role->permission_id = $request->get('permission_id');
        $permission_role->role_id = $request->get('role_id');
        $permission_role->save();

        return Response::json($permission_role, 200);
    }

    /**
     * Display the specified role.
     *
     * @param  int $role_id
     * @param $permission_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($permission_id, $role_id)
    {
        $permission_role = PermissionRole::where(['role_id', '=', $role_id],
             ['permission_id', '=', $permission_id])->first();

        if ($permission_role)
            return Response::json($permission_role, 200);
        else
            return Response::json($permission_role, 404);
    }

    /**
     * Update the specified role in storage.
     *
     * @param PermissionRoleRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PermissionRoleRequest $request,$id)
    {
        return RestHelper::update(PermissionRole::class,$request->all(),$id);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($permission_id,$role_id)
    {
        $permission_role = PermissionRole::where('permission_id', $permission_id)
            ->where('role_id', $role_id)
            ->first();

        if ($permission_role){
            $permission_role->delete();
            return Response::json(["role_user" => $permission_role, "message" => "Object has been deleted"], 200);
        }
        else{
            return Response::json(["message" => "Object not found role_id=". $role_id . " permission_id=" . $permission_id], 404);
        }
    }

}
