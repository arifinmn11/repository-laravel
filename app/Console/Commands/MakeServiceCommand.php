<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}Service.php");

        if (file_exists($path)) {
            $this->error("Service {$name}Service already exists!");
            return;
        }

        $stub = $this->getStub();
        (new Filesystem)->ensureDirectoryExists(app_path('Services'));
        file_put_contents($path, str_replace('{{name}}', $name, $stub));

        $this->info("Service {$name}Service created successfully.");
    }

    protected function getStub()
    {
        return <<<PHP
<?php

namespace App\Services;

use App\Models\{{name}};
use App\Repositories\{{name}}IRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class {{name}}Service
{
    protected \$repository;

    public function __construct({{name}}IRepository \$repository)
    {
        \$this->repository = \$repository;
    }

    public function get{{name}}ById(\$id): {{name}}
    {
        return \$this->repository->findById(\$id);
    }

    public function create{{name}}(array \$data): {{name}}
    {
        return \$this->repository->create(\$data);
    }

    public function update{{name}}ById(array \$data, \$id): {{name}}
    {
        return \$this->repository->updateById(\$data, \$id);
    }

    public function delete{{name}}ById(\$id): bool
    {
        return \$this->repository->deleteById(\$id);
    }

    public function list{{name}}(\$limit = null, \$search = null, \$isActive = true): Collection
    {
        return \$this->repository->getList(\$limit, \$search, \$isActive);
    }

    public function getPagination(int \$limit = 10, ?string \$search = null, string \$sortBy = 'id|asc', \$filters = [], \$customFilters): LengthAwarePaginator
    {
        return \$this->repository->getPagination(\$limit, \$search, \$sortBy, \$filters, \$customFilters);
    }
}

PHP;
    }
}
