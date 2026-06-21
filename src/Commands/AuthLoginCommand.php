<?php

namespace Alpayklncrsln\RuleSchema\Commands;

class AuthLoginCommand extends BaseAuthCommand
{
    protected $signature = 'rule-schema:auth:login';

    protected $description = 'RuleSchema Auth Login request creation';

    protected function getAuthType(): string
    {
        return 'Auth/Login';
    }

    protected function getRulesContent(): string
    {
        return 'DefaultRuleSchema::login();';
    }
}
