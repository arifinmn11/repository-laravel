---
title: Structure, Command and Repository Service Pattern
date: 2024-05-06
category: Features
excerpt: Learn about Prezet's powerful image features including automatic optimization and interactive zoom capabilities.
image: /prezet/img/ogimages/features-images.webp
---

# Directory Structure

```
app/
├── Repositories/
│   ├── TestIRepository.php
│   └── TestRepository.php
├── Services/
│   └── TestService.php
└── Request/
    └──Test/
        └── TestCreateRequest.php
        └── TestUpdateRequest.php
└── Resource/
    └──Test/
        └── TestResource.php
        └── TestOptionResource.php
        └── TestPaginationResource.php
└── Providers/
    └── AppServiceProvider.php
```

## 1. Repository Interface and Implementation

Command create repository :

```
php artisan make:repository Test
```

App\Repositories\TestIRepository.php

```php<?php

namespace App\Repositories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TestIRepository
{
    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator;
    public function getList($limit = null, $search = null, $isActive = true): Collection;
    public function create(array $data): Test;
    public function updateById(array $data, $id): Test;
    public function deleteById($id): bool;
    public function findById($id): Test;
}
```

App\Repositories\TestRepository.php

```php
<?php

namespace App\Repositories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TestRepository extends BaseRepository implements TestIRepository
{
    protected array $searchableFields = [];

    public function __construct(Test $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated Test data.
     */
    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator
    {
        $this->applySearchKeyword($search, $this->searchableFields);
        $this->applyFilters($filters);
        $this->applySortBy($sortBy);


        return $this->getPaginatedResults($limit);
    }

    public function getList($limit = null, $search = null, $isActive = true): Collection
    {
        $query = Test::where('is_active', $isActive)
            ->search($search)
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })->get();

        return $query;
    }
    public function create(array $data): Test
    {
        try {
            $result = Test::create($data);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }
    public function updateById(array $data, $id): Test
    {
        try {
            $query = Test::findOrFail($id);
            $query->update($data);
            return $query;
        } catch (\Throwable $th) {
            throw new \Exception('Update failed');
        }
    }
    public function deleteById($id): bool
    {
        try {
            $query = Test::findOrFail($id);
            $query->delete();
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Delete failed');
        }
    }
    public function findById($id): Test
    {
        try {
            return Test::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception('Test not found');
        }
    }
}
```

## 2. Service 

```php
<?php

namespace App\Services;

use App\Models\Test;
use App\Repositories\TestIRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TestService
{
    protected $repository;

    public function __construct(TestIRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getTestById($id): Test
    {
        return $this->repository->findById($id);
    }

    public function createTest(array $data): Test
    {
        return $this->repository->create($data);
    }

    public function updateTestById(array $data, $id): Test
    {
        return $this->repository->updateById($data, $id);
    }

    public function deleteTestById($id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function listTest($limit = null, $search = null, $isActive = true): Collection
    {
        return $this->repository->getList($limit, $search, $isActive);
    }

    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters): LengthAwarePaginator
    {
        return $this->repository->getPagination($limit, $search, $sortBy, $filters, $customFilters);
    }
}

```

## 3. Repository Service Provider

```php
<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\TestIRepository::class, \App\Repositories\TestRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}

```

## 4. Request Classes

```php
namespace App\Http\Requests\Test;

use App\Http\Requests\TestFormRequest;

class TestCreateRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:branch,code',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ];
    }
}

```

```php
namespace App\Http\Requests\Test;

use App\Http\Requests\BaseFormRequest;

class TestUpdateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => ['required|string|max:100', 'unique:branch,code,' . $this->id,],
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|string|max:100',
            'is_active' => 'nullable|string|max:100',
        ];
    }
}

```

## 5. Response Classes

### 5.1 Resource  
App\Http\Resources\Test\TestResource.php
```php
namespace App\Http\Resources\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];
    }
}

```

### 5.2 Pagination 
App\Http\Resources\Test\TestPaginationResource.php
```php
namespace App\Http\Resources\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestPaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" => TestResource::collection($this->items()),
            'pagination' => [
                'total'         => $this->total(),
                'per_page'      => $this->perPage(),
                'current_page'  => $this->currentPage(),
                'last_page'     => $this->lastPage(),
                'from'          => $this->firstItem(),
                'to'            => $this->lastItem()
            ]
        ];
    }
}

```

### 5.3 Option
App\Http\Resources\Test\TestOptionResource.php
```php
namespace App\Http\Resources\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}

```
App\Http\Resources\Test\TestOptionCollection.php
```php
<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestOptionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return TestOptionResource::collection($this->collection);
    }
}

```

## 6. Controller

Command create repository :

```
php artisan make:controller Api/v1/Test
```
App\Http\Controllers\Api\v1\TestControllerApi.php
```php
<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Test\TestCreateRequest;
use App\Http\Requests\Test\TestUpdateRequest;
use App\Http\Resources\Test\TestOptionCollection;
use App\Http\Resources\Test\TestOptionResource;
use App\Http\Resources\Test\TestPaginationResource;
use App\Http\Resources\Test\TestResource;
use App\Services\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestControllerApi extends BaseController
{
    protected $service;

    public function __construct(TestService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $search = $request->get('search', null);
        $sortBy = $request->get('sort_by', 'id|asc');

        $filters = [];
        //Ex:
        //$filters['name'] = $request->get('name', null);
        //$filters['is_active'] = $request->get('is_active', true);

        $customFilters = [];
        //Ex:
        //$customFilters['names'] = $request->get('names', null) ? explode(',', $request->get('names')) : null;

        $results = $this->service->getPagination($limit, $search, $sortBy, $filters, $customFilters);

        return $this->successResponse(new TestPaginationResource($results));
    }

    public function options(Request $request): JsonResponse
    {
        $limit = $request->get('limit', null);
        $search = $request->get('search', null);
        $isActive = $request->get('is_active', true);

        $results = $this->service->listTest($limit, $search, $isActive);

        return $this->successResponse(new TestOptionCollection($results));
    }

    public function store(TestCreateRequest $request): JsonResponse
    {
        try {
            $result = $this->service->createTest($request->validated());
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Create Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($result);
    }

    public function update(TestUpdateRequest $request, $id): JsonResponse
    {
        try {
            $result = $this->service->updateTestById($request->validated(), $id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Update Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($result);
    }

    public function show($id): JsonResponse
    {
        try {
            $result = $this->service->getTestById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Get Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(new TestResource($result));
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->service->deleteTestById($id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Delete Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(null, Response::HTTP_NO_CONTENT);
    }
}
```
