<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Interfaces\RuleSchemaInterface;
use Alpayklncrsln\RuleSchema\Table\TableBuilder;
use Alpayklncrsln\RuleSchema\Traits\withCacheTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RuleSchema implements RuleSchemaInterface
{
    use withCacheTrait;

    protected array $rules = [];

    protected array $messages = [];

    protected bool $isBail = false;

    public function __construct(Rule|array ...$rules)
    {
        if (count($rules) > 0) {
            $this->merge(...$rules);
        }
    }

    public static function create(Rule|array ...$rules): self
    {
        return new RuleSchema(...$rules);
    }

    public function merge(Rule|array ...$rules): self
    {
        if (!$this->existsCacheData()) {
            if ($rules !==[]){
                $rules= Arr::flatten($rules);
                foreach ($rules as $rule) {
                    if ($rule instanceof Rule) {
                        $this->rules = array_merge($this->rules, $rule->getRule());
                        $this->messages = array_merge($this->messages, $rule->getMessage());
                    } else {
                        throw new \Exception("Invalid rule type. Must be an instance of " . Rule::class . " of them.");
                    }

                }
            }

        }

        return $this;
    }

    public
    function getRules(): array
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


    public
    function getMessages(): array
    {
        return $this->messages;
    }

    public
    function when(bool $condition, Rule|array ...$rules): self
    {
        if ($condition && !$this->existsCacheData()) {
            $this->merge($rules);
        }

        return $this;
    }

    public
    function expect(array $attributes): self
    {
        if (!$this->existsCacheData()) {
            foreach ($attributes as $attribute) {
                unset($this->rules[$attribute]);
            }
        }

        return $this;
    }

    public
    function existsMerge($attribute, Rule|array ...$rules): self
    {
        if (!$this->existsCacheData()) {
            $this->when(isset($this->rules[$attribute]), $rules);
        }

        return $this;
    }

    public
    function auth(Rule|array ...$rules): self
    {
        if (!$this->existsCacheData()) {
            $this->when(Auth::check(),$rules);
        }

        return $this;
    }

    public
    function notAuth(Rule|array ...$rules): self
    {
        if (!$this->existsCacheData()) {
            $this->when(!Auth::check(),$rules);
        }

        return $this;
    }

    public
    static function model(string|Model $table): RuleSchema
    {
        return TableBuilder::create($table)->getTableRuleSchema();

    }

    public
    function bailed(): self
    {
        if (!$this->existsCacheData()) {
            $this->isBail = true;
        }

        return $this;
    }

    public
    function arraySchema(string $attribute, array $rules, bool $isMultiple = true, array $methods = ['POST', 'PUT', 'PATCH', 'DELETE', 'GET']): self
    {
        if (!$this->existsCacheData() && in_array(Request::method(), $methods)) {
            foreach ($rules as $rule) {
                $this->rules[$attribute . ($isMultiple ? '.*' : '') . '.' . $rule->getAttribute()] = $rule->getRule()[$rule->getAttribute()];
            }
        }

        return $this;
    }

    public
    function add(Rule $rule): self
    {
        if (!$this->existsCacheData()) {
            $this->rules[$rule->getAttribute()] = $rule->getRule()[$rule->getAttribute()];
        }

        return $this;
    }

    public
    function postSchema(Rule|array ...$rules): self
    {
        $this->when(Request::isMethod('POST'), ...$rules);

        return $this;
    }

    public
    function putSchema(array $rules): self
    {
        $this->when(Request::isMethod('PUT'), $rules);

        return $this;
    }

    public
    function patchSchema(Rule|array ...$rules): self
    {
        $this->when(Request::isMethod('PATCH'), ...$rules);

        return $this;
    }

    public
    function matchSchema(array $methods = ['put', 'patch'], Rule|array ...$rules): self
    {
        $this->when(in_array(Request::method(), $methods), ...$rules);

        return $this;
    }

    public
    function deleteSchema(Rule|array ...$rules): self
    {
        $this->when(Request::isMethod('DELETE'), ...$rules);

        return $this;
    }
}
