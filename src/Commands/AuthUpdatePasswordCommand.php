<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AuthUpdatePasswordCommand extends Command
{
    protected $signature = 'rule-schema:auth:update-password';

    protected $description = 'RuleSchema Auth update password request creation';

    public function handle(): int
    {
        $stubPath = __DIR__.'/../stubs/rule-schema-custom.stub';
        $name = str_replace('/', '\\', 'Auth/UpdatePassword');
        $namespace = 'App\\Http\\Requests\\'.Str::beforeLast($name, '\\');
        $targetPath = base_path('app/Http/Requests/'.str_replace(['\\', '/'], '/', $name).'Request.php');

        if (File::exists($targetPath)) {
            $this->error("Request already exists: {$targetPath}");

            return self::FAILURE;
        }

        if (! File::exists($stubPath)) {
            $this->error("Stub not found: {$stubPath}");

            return self::FAILURE;
        }

        $rules = 'DefaultRuleSchema::updatePassword();';
        $stubContent = File::get($stubPath);
        $stubContent = str_replace(
            ['{{namespace}}', '{{class}}', '{{content}}'],
            [$namespace,  'UpdatePasswordRequest', rtrim($rules, ",\n")],
            $stubContent
        );

        File::ensureDirectoryExists(dirname($targetPath));
        File::put($targetPath, $stubContent);

        $this->info("Request class created: {$targetPath}");

        return self::SUCCESS;
    }
}
