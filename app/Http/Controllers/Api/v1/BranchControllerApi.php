<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchCreateRequest;
use App\Http\Resources\BranchPaginationResource;
use App\Http\Responses\BaseResponse;
use App\Models\Branch;
use App\Repository\BranchRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BranchControllerApi extends Controller
{
    use ApiResponse;

    protected $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $search = $request->get('search', null);
        $page = $request->get('page', 1);
        $sort = $request->get('sort', null);

        $branches = $this->branchRepository->pagination($limit, $search, $page);
        
        return $this->successResponse(new BranchPaginationResource($branches));
    }

    public function options(Request $request)
    {
        $limit = $request->get('limit', null);
        $search = $request->get('search', null);

        $branches = $this->branchRepository->options($limit, $search);

        return $this->successResponse($branches);
    }

    public function store(BranchCreateRequest $request)
    {
        $data = $request->all();
        try {
            $branch = $this->branchRepository->create($data);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Create Failed');
        }

        return $this->successResponse($branch);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        try {
            $branch = $this->branchRepository->updateById($data, $id);
        } catch (\Throwable $th) {

            return $this->errorResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR, 'Update Failed');
        }

        return $this->successResponse($branch);
    }

    public function destroy($id)
    {
        try {
            $this->branchRepository->deleteById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 404, 'Data not found');
        }

        return $this->successResponse(null);
    }

    public function show($id)
    {
        try {
            $branch = $this->branchRepository->getById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 404, 'Data not found');
        }

        return $this->successResponse($branch);
    }
}
