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

        interface {$name}IRepository
        {
            public function exampleMethod();
        }

        PHP;
    }

    protected function getRepositoryStub($name)
    {
        return <<<PHP
        <?php

        namespace App\Repositories;

        class {$name}Repository implements {$name}IRepository
        {
            public function exampleMethod()
            {
                return "Hello from {$name}Repository!";
            }
        }
        PHP;
    }
}
