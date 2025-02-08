<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\BranchCreateRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use App\Services\BranchService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BranchControllerApi extends Controller
{
    use ApiResponse;

    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $search = $request->get('search', null);

        $branches = $this->branchService->listBranch($limit, $search);

        return $this->successResponse($branches);
    }

    public function options(Request $request)
    {
        $limit = $request->get('limit', null);
        $search = $request->get('search', null);

        $branches = $this->branchService->listBranch($limit, $search);

        return $this->successResponse($branches);
    }

    public function store(BranchCreateRequest $request)
    {
        try {
            $branch = $this->branchService->createBranch($request->validated());
        } catch (\Throwable $th) {
            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Create Failed');
        }

        return $this->successResponse($branch);
    }

    public function update(BranchUpdateRequest $request, $id)
    {
        try {
            $branch = $this->branchService->updateBranchById($request->validated(), $id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Update Failed');
        }

        return $this->successResponse($branch);
    }

    public function show($id)
    {
        try {
            $branch = $this->branchService->getBranchById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Get Failed');
        }

        return $this->successResponse($branch);
    }

    public function destroy($id)
    {
        try {
            $this->branchService->deleteBranchById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Delete Failed');
        }

        return $this->successResponse(null, Response::HTTP_NO_CONTENT);
    }
}
