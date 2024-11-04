<?php

namespace Alpayklncrsln\RuleSchema\Table;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TableBuilder
{
    protected string $table = '';

    protected array $tableColumns = [];

    protected array $expectedColumns = [];
    protected null|RuleSchema $ruleSchema = null;

    protected null|Rule $rule = null;

    public function __construct(string|Model $table)
    {
        if (is_string($table)) {
            $this->setTable($table);
        } else {
            $this->modelTableName($table);
        }
        $this->ruleSchema = RuleSchema::create();
        $this->setExpectedColumns(['id', 'created_at', 'updated_at', 'deleted_at']);
        $this->createTableSchema();
        $this->generateRuleSchema();
    }
    public static function create(string|Model $table):self
    {
        return new self($table);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }


    private function modelTableName(Model $model): void
    {
        $mode = new $model();
        $this->setTable($mode->getTable());
        unset($mode);
    }

    public function getTableColumns(): array
    {
        return $this->tableColumns;
    }

    private function setTableColumns(array $columns): void
    {
        $this->tableColumns = $columns;
    }


    public function getExpectedColumns(): array
    {
        return $this->expectedColumns;
    }

    public function setExpectedColumns(array $columns): self
    {
        $this->expectedColumns = $columns;
        return $this;
    }

    private function getRuleSchema(): RuleSchema
    {
        if (is_null($this->ruleSchema)) {
            $this->ruleSchema = RuleSchema::model($this->table);
        }
        return $this->ruleSchema;
    }

    private function createTableSchema(): void
    {
        $this->setTableColumns(collect(Schema::getColumns($this->getTable()))
            ->filter(function ($column) {
                return !in_array($column['name'], $this->getExpectedColumns()) ? $column : null;
            })->toArray());
    }

    private function generateRuleSchema(): void
    {
        foreach ($this->getTableColumns() as $column) {
            $this->rule = Rule::make($column['name']);
            $this->getRuleSchema()->merge($this->generateRule($this->rule, $column));
            $this->rule = null;
        }
    }

    private function generateRule(Rule $rule, array $column): Rule
    {
        $column = new TableColumn(
            $column['name'],
            $column['type'],
            $column['type_name'],
            $column['default'],
            $column['nullable'],
            $column['comment'],
            $column['auto_increment']
        );

        switch ($column->typeName) {
            case 'int':
                $rule->numeric();
                break;
            case 'varchar':
                $rule->string()->max((int) Str::between($column->type, '(', ')'));
                break;
            case 'text':
                $rule->string();
                break;
            case 'tinyint':
                if ((int) Str::between($column->type, '(', ')') === 1) {
                    $rule->boolean();
                }else{
                    $rule->numeric();
                }
                break;
            case 'date':
                $rule->date();
                break;
            case 'timestamp':
            case 'datetime':
                $rule->dateTime();
                break;
            case 'double':
            case 'float':
                case 'decimal':
                $rule->decimal(8);
                break;
            default:
                $rule->string();
                break;
                case 'json':
                    $rule->json();
                    break;
        }
        if ($column->nullable) {
            $rule->nullable();
        }
        unset($column);
        return $rule;
    }

    public function getTableRuleSchema():RuleSchema
    {
        return $this->getRuleSchema();
    }

}
