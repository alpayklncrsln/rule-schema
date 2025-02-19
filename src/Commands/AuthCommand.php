<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;

class AuthCommand extends Command
{
    protected $signature = 'rule-schema:auth {type} {--force : Force the operation to run when in production.}';

    protected $description = 'RuleSchema Auth request creation';

    public function handle(): int
    {
        $type = $this->argument('type');

        switch ($type) {
            case 'all':
                $this->call('rule-schema:auth:all');
                break;
            case 'login':
                $this->call('rule-schema:auth:login');
                break;
            case 'register':
                $this->call('rule-schema:auth:register');
                break;
            case 'reset-password':
                $this->call('rule-schema:auth:reset-password');
                break;
            case 'update-password':
                $this->call('rule-schema:auth:update-password');
                break;
            default:
                $this->error('Invalid type provided.');
                $this->warn('Available types: login, register, reset-password, update-password');

                return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
