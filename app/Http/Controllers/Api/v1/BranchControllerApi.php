<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\BranchCreateRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use App\Http\Resources\Branch\BranchOptionCollection;
use App\Http\Resources\Branch\BranchOptionResource;
use App\Http\Resources\Branch\BranchPaginationResource;
use App\Http\Resources\Branch\BranchResource;
use App\Services\BranchService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BranchControllerApi extends BaseController
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $search = $request->get('search', null);
        $sortBy = $request->get('sort_by', 'id|asc');

        $filters = [];
        $filters['name'] = $request->get('name', null);
        $filters['address'] = $request->get('address', null);
        $filters['phone'] = $request->get('phone', null);
        $filters['email'] = $request->get('email', null);
        $filters['is_active'] = $request->get('is_active', true);

        $customFilters = [];
        $customFilters['names'] = $request->get('names', null) ? explode(',', $request->get('names')) : null;

        $branches = $this->branchService->getPaginatedBranches($limit, $search, $sortBy, $filters, $customFilters);

        return $this->successResponse(new BranchPaginationResource($branches));
    }

    public function options(Request $request): JsonResponse
    {
        $limit = $request->get('limit', null);
        $search = $request->get('search', null);
        $isActive = $request->get('is_active', true);

        $branches = $this->branchService->listBranch($limit, $search, $isActive);

        return $this->successResponse(new BranchOptionCollection($branches));
    }

    public function store(BranchCreateRequest $request): JsonResponse
    {
        try {
            $branch = $this->branchService->createBranch($request->validated());
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Create Failed', Response::HTTP_INTERNAL_SERVER_ERROR,);
        }

        return $this->successResponse($branch);
    }

    public function update(BranchUpdateRequest $request, $id): JsonResponse
    {
        try {
            $branch = $this->branchService->updateBranchById($request->validated(), $id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Update Failed');
        }

        return $this->successResponse($branch);
    }

    public function show($id): JsonResponse
    {
        try {
            $branch = $this->branchService->getBranchById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Get Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(new BranchResource($branch));
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->branchService->deleteBranchById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Delete Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(null, Response::HTTP_NO_CONTENT);
    }
}
