<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\UserIRepository::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Repositories\BranchIRepository::class, \App\Repositories\BranchRepository::class);
        $this->app->bind(\App\Repositories\RoleIRepository::class, \App\Repositories\RoleRepository::class);
        $this->app->bind(\App\Repositories\PermissionIRepository::class, \App\Repositories\PermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        //     return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        // });


        // Ensure Laravel uses our custom stub for controllers
        Artisan::command('make:controller {name}', function ($name) {
            // Define stub path
            $stubPath = base_path('stubs/controller.api.stub'); // Adjust if needed
            $controllerPath = app_path("Http/Controllers/{$name}ControllerApi.php"); // Preserve original file name

            if (!File::exists($stubPath)) {
                $this->error("Stub file not found at: {$stubPath}");
                return;
            }

            // Ensure the directory exists
            $directory = dirname($controllerPath);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0777, true, true);
            }

            if (File::exists($controllerPath)) {
                $this->error("Controller {$name} already exists at {$controllerPath}");
                return;
            }

            // Read the stub
            $stub = File::get($stubPath);

            // Extract the last segment after `/` as the real class name
            $segments = explode('/', $name);
            $className = end($segments); // "Test" from "Api/v1/Test"

            // Replace placeholders in stub
            $stub = str_replace('{{ class }}', $className, $stub);

            // Write the final controller file
            File::put($controllerPath, $stub);

            Artisan::call('make:request', ['name' => $className . '/' . $className . 'CreateRequest']);
            Artisan::call('make:request', ['name' => $className . '/' . $className . 'UpdateRequest']);
            Artisan::call('make:resource', ['name' => $className . '/' . $className . 'TestResource']);
            Artisan::call('make:resource', ['name' => $className . '/' . $className . 'PaginationResource']);
            Artisan::call('make:resource', ['name' => $className . '/' . $className . 'OptionCollection']);

            $this->info("Controller {$name} created successfully at {$controllerPath}");
        })->describe('Create a new API controller using a custom stub.');
    }
}
