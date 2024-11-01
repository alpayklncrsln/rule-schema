<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\RuleSchemaInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RuleSchema implements RuleSchemaInterface
{
    protected array $rules = [];

    public function __construct( ...$rules) {

        foreach ($rules as $rule) {
            $this->rules = array_merge($this->rules, $rule->getRule());
        }
    }

    public static function create( Rule...$rules) : self
    {
        return new RuleSchema(...$rules);
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function merge(Rule ...$rules): self
    {
        foreach ($rules as $rule) {
            $this->rules = array_merge($this->rules, $rule->getRule());
        }
        return $this;
    }

    public function ruleClass(string $attribute,ValidationRule $class): self
    {
        $this->rules[$attribute][] = $class;
        return $this;
    }

    public function when(bool $condition, Rule ...$rules): self
    {
        if ($condition) {
            $this->merge(...$rules);
        }
        return $this;
    }

    public function expect(string ...$attributes): self
    {
        foreach ($attributes as$attribute ) {
            unset($this->rules[$attribute]);
        }
        return $this;
    }

    public function existsMerge($attribute, Rule ...$rules): self
    {
        if (isset($this->rules[$attribute])) {
            $this->merge(...$rules);
        }
        return $this;
    }
    public function auth(Rule ...$rules): self
    {
        if (Auth::check()) $this->merge(...$rules);
        return $this;
    }
    public function notAuth(Rule ...$rules): self
    {
        if (!Auth::check()) $this->merge(...$rules);
        return $this;
    }

    public static function model(string $table) : RuleSchema
    {
        $ruleSchema = RuleSchema::create();
        $columns = collect(Schema::getColumns($table))
            ->filter(function($column) {
                return !in_array($column['name'], ['id', 'created_at', 'updated_at']) ? $column : null;
            });
        $rule = null;
        foreach ($columns as $column) {
            $rule = Rule::make($column['name']);
            if (Str::StartsWith($column['type'], 'varchar')) {
                $rule->string()->max((int) Str::between($column['type'], '(', ')'));
            }
            if ($column['type'] === 'int') {
                $rule->numeric();
            }

            if ($column['type'] === 'text') {
                $rule->string();
            }

            if ($column['type'] === 'tinyint') {
                $rule->boolean();
            }

            if ($column['type'] === 'date') {
                $rule->date();
            }

            if ($column['type'] === 'datetime') {
                $rule->dateTime();
            }

            if ($column['type'] === 'timestamp') {
                $rule->dateTime();
            }

            if ($column['type'] === 'json') {
                $rule->json();
            }

            if ($column['type'] === 'jsonb') {
                $rule->json();
            }

            if ($column['type'] === 'float') {
                $rule->numeric();
            }

            if ($column['type'] === 'decimal') {
                $rule->decimal(8);
            }

            if ($column['type'] === 'bigint') {
                $rule->numeric();
            }
            $ruleSchema->merge($rule);
        }
            return $ruleSchema;
    }
}
