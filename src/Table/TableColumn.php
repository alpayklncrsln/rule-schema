<?php

namespace Alpayklncrsln\RuleSchema\Table;

class TableColumn
{

    public function __construct(
        public string $name,
        public string $type,
        public $typeName,
        public string|null $default="",
        public bool $nullable = false,
        public null|string $comment="",
        public bool $autoIncrement = false,

    ){
        //..
    }
}
