<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Default\DefaultRuleSchema;
use Alpayklncrsln\RuleSchema\Interfaces\RuleSchemaInterface;
use Alpayklncrsln\RuleSchema\Table\TableBuilder;
use Alpayklncrsln\RuleSchema\Traits\WithCacheTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class RuleSchema implements RuleSchemaInterface
{
    use WithCacheTrait;

    protected array $rules = [];

    protected array $messages = [];

    protected bool $isBail = false;

    public function __construct(BaseRuleBuilder|RuleSchema|array ...$rules)
    {
        if (count($rules) > 0) {
            $this->merge(...$rules);
        }
    }

    public static function create(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        return new RuleSchema(...$rules);
    }

    public function merge(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        if (! $this->existsCacheData()) {
            if ($rules !== []) {
                $rules = Arr::flatten($rules);
                foreach ($rules as $rule) {
                    if ($rule instanceof BaseRuleBuilder) {
                        $this->rules = array_merge($this->rules, $rule->getRule());
                        $this->messages = array_merge($this->messages, $rule->getMessage());
                    } elseif ($rule instanceof RuleSchema) {
                        $this->rules = array_merge($this->rules, $rule->getRules());
                        $this->messages = array_merge($this->messages, $rule->getMessages());
                    } else {
                        throw new Exception('Invalid rule type. Must be an instance of ' . BaseRuleBuilder::class . ' or ' . RuleSchema::class . ' of them.');
                    }

                }
            }

        }

        return $this;
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

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function when(bool $condition, BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        if ($condition && ! $this->existsCacheData()) {
            $this->merge($rules);
        }

        return $this;
    }

    public function expect(array $attributes): self
    {
        if (! $this->existsCacheData()) {
            $rules = $this->rules;
            foreach ($attributes as $attribute) {
                unset($rules[$attribute]);
            }
            $this->rules = $rules;
        }

        return $this;
    }

    public function existsMerge($attribute, BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        if (! $this->existsCacheData()) {
            $this->when(isset($this->rules[$attribute]), $rules);
        }

        return $this;
    }

    public function auth(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        if (! $this->existsCacheData()) {
            $this->when(Auth::check(), $rules);
        }

        return $this;
    }

    public function notAuth(BaseRuleBuilder|RuleSchema|array ...$rules): self
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

    public function arraySchema(string $attribute, array $rules, bool $isMultiple = true, array $methods = ['POST', 'PUT', 'PATCH', 'DELETE', 'GET']): self
    {
        if (! $this->existsCacheData() && in_array(Request::method(), $methods)) {
            foreach ($rules as $rule) {
                if ($rule instanceof BaseRuleBuilder) {
                    $this->rules[$attribute.($isMultiple ? '.*' : '').'.'.$rule->getAttribute()] = $rule->getRule()[$rule->getAttribute()];
                    if ($rule->getMessage() !== []) {
                        foreach ($rule->getMessage() as $key => $message) {
                            $this->messages[$attribute.($isMultiple ? '.*' : '').'.'.$key] = $message;
                        }
                    }
                } elseif ($rule instanceof RuleSchema) {

                    foreach ($rule->getRules() as $key => $value) {
                        $this->rules[$attribute.($isMultiple ? '.*' : '').'.'.$key] = $value;
                    }
                    if ($rule->getMessages() !== []) {
                        foreach ($rule->getMessages() as $key => $message) {
                            $this->messages[$attribute.($isMultiple ? '.*' : '').'.'.$key] = $message;
                        }
                    }
                } else {
                    throw new Exception('Invalid rule type. Must be an instance of ' . BaseRuleBuilder::class . ' of them.');
                }

            }
        }

        return $this;
    }

    public function add(BaseRuleBuilder $rule): self
    {
        if (! $this->existsCacheData()) {
            $this->rules[$rule->getAttribute()] = $rule->getRule()[$rule->getAttribute()];
        }

        return $this;
    }

    public function ruleClass(string $attribute, mixed $rule): self
    {
        if (!$this->existsCacheData()) {
            if (!isset($this->rules[$attribute])) {
                $this->rules[$attribute] = [];
            }
            $this->rules[$attribute][] = $rule;
        }

        return $this;
    }

    public function postSchema(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        $this->when(Request::isMethod('POST'), ...$rules);

        return $this;
    }

    public function putSchema(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        $this->when(Request::isMethod('PUT'), $rules);

        return $this;
    }

    public function patchSchema(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        $this->when(Request::isMethod('PATCH'), ...$rules);

        return $this;
    }

    public function matchSchema(array $methods = ['put', 'patch'], BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        $this->when(in_array(Request::method(), $methods), ...$rules);

        return $this;
    }

    public function deleteSchema(BaseRuleBuilder|RuleSchema|array ...$rules): self
    {
        $this->when(Request::isMethod('DELETE'), ...$rules);

        return $this;
    }

    public function validate(?array $data = null): array
    {
        if (is_null($data)) {
            return Request::validate($this->getRules(), $this->getMessages());
        }

        return Validator::make($data, $this->getRules(), $this->getMessages())->validate();
    }

    public static function login(bool $remember = true): self
    {
        return DefaultRuleSchema::login($remember);
    }

    public static function register(bool $passwordConfirmation = true): self
    {
        return DefaultRuleSchema::register($passwordConfirmation);
    }

    public static function resetPassword(): self
    {
        return DefaultRuleSchema::resetPassword();
    }

    public static function updatePassword(): self
    {
        return DefaultRuleSchema::updatePassword();
    }

    public static function contact(): self
    {
        return DefaultRuleSchema::contact();
    }

    public static function feedback(): self
    {
        return DefaultRuleSchema::feedback();
    }
}
