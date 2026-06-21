<?php

namespace Alpayklncrsln\RuleSchema\Commands;

class AuthRegisterCommand extends BaseAuthCommand
{
    protected $signature = 'rule-schema:auth:register';

    protected $description = 'RuleSchema Auth Register request creation';

    protected function getAuthType(): string
    {
        return 'Auth/Register';
    }

    protected function getRulesContent(): string
    {
        return 'DefaultRuleSchema::register();';
    }
}
