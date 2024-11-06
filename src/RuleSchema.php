<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\RuleSchemaInterface;
use Alpayklncrsln\RuleSchema\Table\TableBuilder;
use Alpayklncrsln\RuleSchema\Traits\withCacheTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RuleSchema implements RuleSchemaInterface
{
    use withCacheTrait;

    protected array $rules = [];

    protected bool $isBail = false;

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
        if ($this->isBail) {
            foreach ($this->rules as $key => $rule) {
                $this->rules[$key][] = 'bail';
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
        if (! $this->existsCacheData()) {
            $this->when(isset($this->rules[$attribute]), ...$rules);
        }

        return $this;
    }

    public function auth(Rule ...$rules): self
    {
        if (! $this->existsCacheData()) {
            $this->when(Auth::check(), ...$rules);
        }

        return $this;
    }

    public function notAuth(Rule ...$rules): self
    {
        if (! $this->existsCacheData()) {
            $this->when(! Auth::check(), ...$rules);
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
            $this->isBail = true;
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

    public function postSchema(Rule ...$rules): self
    {
        $this->when(Request::isMethod('POST'), ...$rules);

        return $this;
    }

    public function putSchema(Rule ...$rules): self
    {
        $this->when(Request::isMethod('PUT'), ...$rules);

        return $this;
    }
}
