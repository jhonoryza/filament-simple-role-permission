<?php

namespace Jhonoryza\Filament\RolePermission\Console;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PolicyGeneratorCommand extends Command
{
    protected $signature = "policy:generate {userModel=User : user model default: User}";

    protected $description = "Generate all policy for predefined permissions";

    protected $userModel = "User";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->userModel = $this->argument("userModel")
            ? $this->argument("userModel")
            : $this->userModel;
        $definedPermissions = Permission::getPredefined();
        foreach ($definedPermissions as $table => $permissions) {
            $this->generatePolicy($table, $permissions);
        }
    }

    private function generatePolicy($table, $permissions): void
    {
        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists(app_path("Policies"));

        $customPath = app()->basePath(
            "stubs/simple-role-permission/genericPolicy.stub"
        );
        $stub = file_exists($customPath)
            ? $customPath
            : "/stubs/policy/genericPolicy.stub";

        $contents = $filesystem->get(__DIR__ . $stub);
        $modelName = Str::studly(Str::singular($table));

        $policyVariables = [
            "class" => $modelName . "Policy",
            "namespacedModel" => "App\\Models\\" . $modelName,
            "namespacedUserModel" => "App\\Models\\" . $this->userModel,
            "namespace" => "App\Policies",
            "user" => $this->userModel,
            "userVariable" => Str::camel($this->userModel),
            "model" => $modelName,
            "modelVariable" =>
                $modelName == $this->userModel
                    ? "model"
                    : Str::lower($modelName),
        ];

        foreach ($permissions as $permission) {
            $key = Permission::match($permission);
            if ($key == "") {
                return;
            }
            $contents = Str::replace(
                "{{ " . $key . " }}",
                $table . "." . $permission,
                $contents
            );
        }

        foreach ($policyVariables as $search => $replace) {
            if ($modelName == "User" && $search == "namespacedModel") {
                $contents = Str::replace(
                    "use {{ namespacedModel }};",
                    "",
                    $contents
                );
            } else {
                $contents = Str::replace(
                    "{{ " . $search . " }}",
                    $replace,
                    $contents
                );
            }
        }

        $filesystem->put(
            app_path("Policies/" . $modelName . "Policy.php"),
            $contents
        );
        $this->comment("Creating Policy: " . $modelName);
    }
}
