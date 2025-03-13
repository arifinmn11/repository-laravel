<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRoleUpdateRequest;
use App\Http\Requests\Role\RolePermissionUpdate;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Resources\Role\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleControllerApi extends BaseController
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function create(Request $request)
    {
        $data = $request->all();

        try {

            $role = $this->roleService->createRole($data);
        } catch (\Throwable $th) {

            return $this->errorResponse($th->getMessage());
        }
        return $this->successResponse($role, 'Role created successfully');
    }

    public function show($id)
    {
        $role = $this->roleService->findRoleById($id);
        $permissions = $this->roleService->getPermissionByRoleId($role->id);

        return $this->successResponse([
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function update(RolePermissionUpdate $request, $id)
    {

        try {
            $data = $request->validated();

            $role = $this->roleService->updateRoleById($data, $id);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
        return $this->successResponse(new RoleResource($role), 'Role created successfully');
    }
}
