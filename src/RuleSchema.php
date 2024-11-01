<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\RuleSchemaInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

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
}
