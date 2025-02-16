<?php

namespace Tests\Unit;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use App\Services\BranchService;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class BranchServiceTest extends TestCase
{

    protected $branchService;

    private const SERVICE_CREATE = 'createBranch';
    private const SERVICE_UPDATE_BY_ID = 'updateBranchById';
    private const SERVICE_GET_BY_ID = 'getBranchById';
    private const SERVICE_GET_LIST = 'listBranch';
    private const SERVICE_PAGINATION = 'paginationBranch';
    private const SERVICE_DELETE_BY_ID = 'deleteBranchById';

    protected function setUp(): void
    {
        parent::setUp();

        $this->branchService = Mockery::mock(BranchService::class)->makePartial();
    }

    public function testGetAllBranch()
    {
        $branches = Mockery::mock(Collection::class);
        $this->branchService
            ->shouldReceive('getList')
            ->andReturn($branches);

        $result = $this->branchService->getList();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testGetBranchById()
    {
        $branch = Mockery::mock(Branch::class);

        $this->branchService
            ->shouldReceive(self::SERVICE_GET_BY_ID)
            ->with(1)
            ->andReturn($branch);

        $result = $this->branchService->getBranchById(1);

        $this->assertInstanceOf(Branch::class, $result);
    }

    public function testCreateBranch()
    {
        $faker = Factory::create();

        $data = [
            'uuid' => $faker->uuid(),
            'name' => $faker->name(),
            'code' => $faker->name(),
            'address' => $faker->address(),
            'phone' =>  $faker->phoneNumber(),
            'email' => $faker->email(),
        ];

        $branch = Mockery::mock(Branch::class);
        $this->branchService->shouldReceive(self::SERVICE_CREATE)
            ->with($data)
            ->andReturn($branch);

        $result = $this->branchService->createBranch($data);

        $this->assertInstanceOf(Branch::class, $result);
    }

    public function testUpdateBranchById()
    {
        $faker = Factory::create();

        $data = [
            'uuid' => $faker->uuid(),
            'name' => $faker->name(),
            'code' => $faker->name(),
            'address' => $faker->address(),
            'phone' =>  $faker->phoneNumber(),
            'email' => $faker->email(),
        ];

        $branch = Mockery::mock(Branch::class);
        $this->branchService->shouldReceive(self::SERVICE_UPDATE_BY_ID)
            ->with($data, 1)
            ->andReturn($branch);

        $result = $this->branchService->updateBranchById($data, 1);

        $this->assertInstanceOf(Branch::class, $result);
    }

    public function testDeleteBranchById()
    {
        $this->branchService
            ->shouldReceive(self::SERVICE_DELETE_BY_ID)
            ->with(1)
            ->andReturn(true);


        $result = $this->branchService->deleteBranchById(1);

        $this->assertTrue($result);
    }

    public function testListBranch()
    {
        $branches = Mockery::mock(Collection::class);
        $this->branchService
            ->shouldReceive(self::SERVICE_GET_LIST)
            ->andReturn($branches);

        $result = $this->branchService->listBranch();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testPaginationBranch()
    {
        $branches = Mockery::mock(Collection::class);
        $this->branchService
            ->shouldReceive(self::SERVICE_PAGINATION)
            ->andReturn($branches);

        $result = $this->branchService->paginationBranch();

        $this->assertInstanceOf(Collection::class, $result);
    }
}
