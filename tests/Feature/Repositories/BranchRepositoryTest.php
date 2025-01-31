<?php

namespace Tests\Feature\Repositories;

use App\Models\Branch;
use App\Repository\BranchRepository;
use App\Repository\BranchRepositoryInterface;
use Database\Factories\BranchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class BranchRepositoryTest extends TestCase
{
    protected $branchMock;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->branchMock = Mockery::mock('alias:' . Branch::class);

        // Inject the mock into the repository
        $this->repository = new BranchRepository(new Branch());
    }
    public function testGetByIdSuccess()
    {
        $branch = new Branch();
        $branch->id = 1;
        $branch->name = 'Main Branch';
        $branch->code = 'MB';
        $branch->address = 'Main Street';
        $branch->phone = '123456789';
        $branch->email = 'email@gmail.com';
        $branch->is_active = true;
        $branch->user_created = 'random';
        $branch->user_updated = 'random';

        $this->branchMock
            ->shouldReceive('findOrFail')
            ->with(1)
            ->once()
            ->andReturn($branch);

        $result = $this->repository->getById(1);

        $this->assertInstanceOf(Branch::class, $result);
        $this->assertEquals($branch->id, $result->id);
        $this->assertEquals($branch->name, $result->name);
    }

    public function testGetByIdFail()
    {
        try {
            $this->repository->getById(null);
        } catch (\Exception $e) {
            $this->assertEquals('Branch not found', $e->getMessage());
        }
    }

    public function testCreateSuccess()
    {
        $branch = new Branch();

        $this->branchMock
            ->shouldReceive('create')
            ->with($branch)
            ->once()
            ->andReturn($branch);

        $result = $this->repository->create($branch);

        $this->assertInstanceOf(Branch::class, $result);
    }
}
