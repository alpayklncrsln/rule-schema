<?php

namespace Alpayklncrsln\RuleSchema\Table;

class TableColumn
{
    public function __construct(
        public string $name,
        public string $type,
        public $typeName,
        public ?string $default = '',
        public bool $nullable = false,
        public ?string $comment = '',
        public bool $autoIncrement = false,

    ) {
        // ..
    }
}
