<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\RuleSchemaInterface;
use Alpayklncrsln\RuleSchema\Table\TableBuilder;
use Alpayklncrsln\RuleSchema\Traits\withCacheTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RuleSchema implements RuleSchemaInterface
{
    use withCacheTrait;

    protected array $rules = [];

    public function __construct(Rule ...$rules)
    {
        if (count($rules) > 0) {
            $this->merge(...$rules);
        }
    }

    public static function create(Rule ...$rules): self
    {
        return new RuleSchema(...$rules);
    }

    public function getRules(): array
    {
        if ($this->isCaching()) {
            if ($this->existsCacheData()) {
                return $this->getCache()->rules;
            } else {
                $this->setCacheData();
            }
        }

        return $this->rules;
    }

    public function merge(Rule ...$rules): self
    {
        if (! $this->existsCacheData()) {
            foreach ($rules as $rule) {
                $this->rules = array_merge($this->rules, $rule->getRule());
            }
        }

        return $this;
    }

    public function ruleClass(string $attribute, ValidationRule $class): self
    {
        if (! $this->existsCacheData()) {
            $this->rules[$attribute][] = $class;
        }

        return $this;
    }

    public function when(bool $condition, Rule ...$rules): self
    {
        if ($condition && ! $this->existsCacheData()) {
            $this->merge(...$rules);
        }

        return $this;
    }

    public function expect(string ...$attributes): self
    {
        if (! $this->existsCacheData()) {
            foreach ($attributes as $attribute) {
                unset($this->rules[$attribute]);
            }
        }

        return $this;
    }

    public function existsMerge($attribute, Rule ...$rules): self
    {
        if (isset($this->rules[$attribute]) && ! $this->existsCacheData()) {
            $this->merge(...$rules);
        }

        return $this;
    }

    public function auth(Rule ...$rules): self
    {
        if (Auth::check() && ! $this->existsCacheData()) {
            $this->merge(...$rules);
        }

        return $this;
    }

    public function notAuth(Rule ...$rules): self
    {
        if (! Auth::check() && ! $this->existsCacheData()) {
            $this->merge(...$rules);
        }

        return $this;
    }

    public static function model(string|Model $table): RuleSchema
    {
        return TableBuilder::create($table)->getTableRuleSchema();

    }

    public function bailed(): self
    {
        if (! $this->existsCacheData()) {
            foreach ($this->rules as $key => $rule) {
                $this->rules[$key]['bail'] = true;
            }
        }

        return $this;
    }

    public function arraySchema(string $attribute, Rule ...$rules): self
    {
        if (! $this->existsCacheData()) {
            foreach ($rules as $rule) {
                $this->rules[$attribute.'.'.$rule->getAttribute()] = $rule->getRule()[$rule->getAttribute()];
            }
        }

        return $this;
    }

    public function add(Rule $rule): self
    {
        if (! $this->existsCacheData()) {
            $this->rules[$rule->getAttribute()] = $rule->getRule()[$rule->getAttribute()];
        }

        return $this;
    }
}
