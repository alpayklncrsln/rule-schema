<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;

class RuleSchemaCommand extends Command
{
    public $signature = 'rule-schema';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
