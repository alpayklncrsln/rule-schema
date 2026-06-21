<?php

namespace Alpayklncrsln\RuleSchema\Commands;

class AuthUpdatePasswordCommand extends BaseAuthCommand
{
    protected $signature = 'rule-schema:auth:update-password';

    protected $description = 'RuleSchema Auth update password request creation';

    protected function getAuthType(): string
    {
        return 'Auth/UpdatePassword';
    }

    protected function getRulesContent(): string
    {
        return 'DefaultRuleSchema::updatePassword();';
    }
}
