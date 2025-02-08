<?php

namespace Tests\Unit;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class BranchRepositoryTest extends TestCase
{
    protected $branchRepository;

    private const REP_LIST = 'list';
    private const REP_CREATE = 'create';
    private const REP_UPDATE_BY_ID = 'updateById';
    private const REP_DELETE_BY_ID = 'delete';
    private const REP_GET_BY_ID = 'findById';
    private const REP_GET_LIST = 'getList';
    private const REP_PAGINATION = 'getPagination';


    protected function setUp(): void
    {
        parent::setUp();

        $this->branchRepository = Mockery::mock(BranchRepository::class)->makePartial();
    }

    public function testGetAllBranch()
    {
        $branches = Mockery::mock(Collection::class);
        $this->branchRepository
            ->shouldReceive(self::REP_GET_LIST)
            ->andReturn($branches);

        $result = $this->branchRepository->getList();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testGetBranchById()
    {
        $user = Mockery::mock(Branch::class);

        $this->branchRepository
            ->shouldReceive(self::REP_GET_BY_ID)
            ->with(1)
            ->andReturn($user);

        $result = $this->branchRepository->findById(1);

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
        $this->branchRepository->shouldReceive(self::REP_CREATE)
            ->with($data)
            ->andReturn($branch);

        $result = $this->branchRepository->create($data);

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

        $this->branchRepository
            ->shouldReceive(self::REP_UPDATE_BY_ID)
            ->with($data, 1)
            ->andReturn($branch);

        $result = $this->branchRepository->updateById($data, 1);

        $this->assertInstanceOf(Branch::class, $result);
    }

    public function testDeleteBranchById()
    {

        $this->branchRepository
            ->shouldReceive(self::REP_DELETE_BY_ID)
            ->with(1)
            ->andReturn(true);

        $result = $this->branchRepository->delete(1);

        $this->assertTrue($result);
    }

    public function testPagination()
    {
        $branches = Mockery::mock(LengthAwarePaginator::class);

        $this->branchRepository
            ->shouldReceive(self::REP_PAGINATION)
            ->with(10, 1)
            ->andReturn($branches);

        $this->assertInstanceOf(LengthAwarePaginator::class, $branches);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
