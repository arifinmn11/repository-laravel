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

class {{name}}Service
{
    public function exampleMethod()
    {
        return "Hello from {{name}}Service!";
    }
}
PHP;
    }
}
