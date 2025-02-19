<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AuthAllCommand extends Command
{
    protected $signature = 'rule-schema:auth:all {--force : Auth folder force delete and create}';

    protected $description = 'RuleSchema Auth Login request creation';

    public function handle(): int
    {
        if ($this->option('force') && File::exists(base_path('app/Http/Requests/Auth'))){
            File::cleanDirectory(base_path('app/Http/Requests/Auth'));
            File::deleteDirectory(base_path('app/Http/Requests/Auth'), true);
        }
        $this->call('rule-schema:auth:login');
        $this->call('rule-schema:auth:register');
        $this->call('rule-schema:auth:reset-password');
        $this->call('rule-schema:auth:update-password');
        $this->info('All requests created successfully.');


        return self::SUCCESS;
    }
}
