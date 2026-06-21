<?php

namespace Alpayklncrsln\RuleSchema\Commands;

class AuthResetPasswordCommand extends BaseAuthCommand
{
    protected $signature = 'rule-schema:auth:reset-password';

    protected $description = 'RuleSchema Auth Login request creation';

    protected function getAuthType(): string
    {
        return 'Auth/ResetPassword';
    }

    protected function getRulesContent(): string
    {
        return 'DefaultRuleSchema::resetPassword();';
    }
}
