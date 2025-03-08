<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class and interface';

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryPath = app_path("Repositories/{$name}Repository.php");
        $interfacePath = app_path("Repositories/{$name}IRepository.php");

        // Check if the files already exist
        if (File::exists($repositoryPath)) {
            $this->error("Repository {$name}Repository already exists!");
            return;
        }

        if (File::exists($interfacePath)) {
            $this->error("Repository Interface {$name}IRepository already exists!");
            return;
        }

        // Ensure directory exists
        File::ensureDirectoryExists(app_path('Repositories'));

        // Create Interface file
        File::put($interfacePath, $this->getInterfaceStub($name));
        $this->info("{$name}IRepository created successfully.");

        // Create Repository file
        File::put($repositoryPath, $this->getRepositoryStub($name));
        $this->info("{$name}Repository created successfully.");
    }

    protected function getInterfaceStub($name)
    {
        return <<<PHP
        <?php

        namespace App\Repositories;

        use App\Models\\{$name};
        use Illuminate\Database\Eloquent\Collection;
        use Illuminate\Pagination\LengthAwarePaginator;

        interface {$name}IRepository
        {
            public function getPagination(int \$limit = 10, ?string \$search = null, string \$sortBy = 'id|asc', \$filters = [], \$customFilters = []): LengthAwarePaginator;
            public function getList(\$limit = null, \$search = null, \$isActive = true): Collection;
            public function create(array \$data): $name;
            public function updateById(array \$data, \$id): $name;
            public function deleteById(\$id): bool;
            public function findById(\$id): $name;
        }

        PHP;
    }

    protected function getRepositoryStub($name)
    {
        return <<<PHP
        <?php

        namespace App\Repositories;

        use App\Models\\{$name};
        use Illuminate\Database\Eloquent\Collection;
        use Illuminate\Pagination\LengthAwarePaginator;

        class {$name}Repository extends BaseRepository implements {$name}IRepository
        {
            protected array \$searchableFields = [];

            public function __construct({$name} \$model)
            {
                parent::__construct(\$model);
            }

            /**
             * Get paginated {$name} data.
             */
            public function getPagination(int \$limit = 10, ?string \$search = null, string \$sortBy = 'id|asc', \$filters = [], \$customFilters = []): LengthAwarePaginator
            {
                \$this->applySearchKeyword(\$search, \$this->searchableFields);
                \$this->applyFilters(\$filters);
                \$this->applySortBy(\$sortBy);


                return \$this->getPaginatedResults(\$limit);
            }

            public function getList(\$limit = null, \$search = null, \$isActive = true): Collection
            {
                \$query = {$name}::where('is_active', \$isActive)
                    ->search(\$search)
                    ->when(\$limit, function (\$query) use (\$limit) {
                        return \$query->limit(\$limit);
                    })->get();

                return \$query;
            }
            public function create(array \$data): $name
            {
                try {
                    \$result = {$name}::create(\$data);

                    return \$result;
                } catch (\Throwable \$th) {
                    throw new \Exception('Create failed');
                }
            }
            public function updateById(array \$data, \$id): $name
            {
                try {
                    \$query = {$name}::findOrFail(\$id);
                    \$query->update(\$data);
                    return \$query;
                } catch (\Throwable \$th) {
                    throw new \Exception('Update failed');
                }
            }
            public function deleteById(\$id): bool
            {
                try {
                    \$query = {$name}::findOrFail(\$id);
                    \$query->delete();
                    return true;
                } catch (\Throwable \$th) {
                    throw new \Exception('Delete failed');
                }
            }
            public function findById(\$id): $name
            {
                try {
                    return {$name}::findOrFail(\$id);
                } catch (\Throwable \$th) {
                    throw new \Exception('{$name} not found');
                }
            }
        }

        PHP;
    }
}
