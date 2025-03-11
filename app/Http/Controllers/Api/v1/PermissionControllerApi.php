<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\PermissionCreateRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionControllerApi extends BaseController
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function store(PermissionCreateRequest $request)
    {
        $data = $request->validated();

        $data['guard_name'] = $data['guard_name'] ?? 'api';

        try {
            $permission = $this->permissionService->createPermission($data);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }

        return $this->successResponse($permission, 'Permission created successfully');
    }
}
