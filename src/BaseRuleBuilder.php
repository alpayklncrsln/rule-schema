<?php

namespace Alpayklncrsln\RuleSchema;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\NotIn;
use Illuminate\Validation\Rules\Unique;
use Laravel\SerializableClosure\SerializableClosure;

abstract class BaseRuleBuilder
{
    use Macroable;

    public string $attribute = '';

    protected array $rule = [];

    protected array $messages = [];

    protected ?array $compiledRules = null;

    protected ?array $compiledMessages = null;

    protected array $sanitizers = [];

    public function __construct(string $attribute = '')
    {
        $this->attribute = $attribute;
    }

    protected function setMessage(string $ruleName, ?string $message = null): void
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;

        if (!is_null($message)) {
            $this->messages[$ruleName] = $message;
        }
    }

    public function getMessage(): array
    {
        if ($this->compiledMessages !== null) {
            return $this->compiledMessages;
        }

        $messages = [];
        foreach ($this->messages as $key => $value) {
            $messages[($this->attribute !== '' ? $this->attribute . '.' : '') . $key] = $value;
        }

        if ($this instanceof ArrayRuleBuilder) {
            if ($this->eachBuilder) {
                $eachMessages = $this->eachBuilder->getMessage();
                foreach ($eachMessages as $subKey => $msg) {
                    $newKey = ($this->attribute !== '' ? $this->attribute . '.*' : '*') . ($subKey !== '' ? '.' . $subKey : '');
                    $messages[$newKey] = $msg;
                }
            }
            if ($this->childBuilders) {
                foreach ($this->childBuilders as $child) {
                    $childMessages = $child->getMessage();
                    foreach ($childMessages as $subKey => $msg) {
                        $newKey = ($this->attribute !== '' ? $this->attribute . '.' : '') . $subKey;
                        $messages[$newKey] = $msg;
                    }
                }
            }
        }

        return $this->compiledMessages = $messages;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    protected function isRuleCheck(string $ruleName): bool
    {
        return array_key_exists($ruleName, $this->rule);
    }

    public function rule(mixed $rule): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        $this->rule['rule.' . count($this->rule)] = $rule;

        return $this;
    }

    public function getRule(): array
    {
        if ($this->compiledRules !== null) {
            return $this->compiledRules;
        }

        $ruleData = [];
        foreach ($this->rule as $key => $value) {
            $ruleData[] = match (true) {
                str_starts_with($key, 'rule.') => $value,
                is_bool($value) => $value ? $key : null,
                is_array($value) => "$key:" . implode(',', $value),
                is_string($value) || is_int($value) => "$key:$value",
                default => $value
            };
        }

        $rules = [$this->attribute => array_filter($ruleData)];

        if ($this instanceof ArrayRuleBuilder) {
            if ($this->eachBuilder) {
                $eachRules = $this->eachBuilder->getRule();
                foreach ($eachRules as $subAttr => $subRules) {
                    $newKey = ($this->attribute !== '' ? $this->attribute . '.*' : '*') . ($subAttr !== '' ? '.' . $subAttr : '');
                    $rules[$newKey] = $subRules;
                }
            }
            if ($this->childBuilders) {
                foreach ($this->childBuilders as $child) {
                    $childRules = $child->getRule();
                    foreach ($childRules as $subAttr => $subRules) {
                        $newKey = ($this->attribute !== '' ? $this->attribute . '.' : '') . $subAttr;
                        $rules[$newKey] = $subRules;
                    }
                }
            }
        }

        return $this->compiledRules = $rules;
    }

    // Generic Rules:
    public function accepted(bool $check = true, ?string $message = null): self
    {
        $this->rule['accepted'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function acceptedIf(string $field, string $value, ?string $message = null): self
    {
        $this->rule['accepted_if'] = $field . ',' . $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function bail(bool $check = true, ?string $message = null): self
    {
        $this->rule['bail'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function boolean(bool $check = true, ?string $message = null): self
    {
        $this->rule['boolean'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function confirmed(bool $check = true, ?string $message = null): self
    {
        $this->rule['confirmed'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function different(string $field, ?string $message = null): self
    {
        $this->rule['different'] = $field;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function exists(Model|string|Exists $table, ?string $column = null, ?string $message = null): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($table instanceof Exists) {
            $this->rule['exists'] = $table;
        } else {
            $tableName = $table instanceof Model ? $table->getTable() : $table;
            $this->rule['exists'] = \Illuminate\Validation\Rule::exists($tableName, $column ?? $this->attribute);
        }
        $this->setMessage('exists', $message);

        return $this;
    }

    public function unique(Model|string|Unique $table, ?string $column = null, ?string $value = null, ?string $message = null): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($table instanceof Unique) {
            $this->rule['unique'] = $table;
        } else {
            $tableName = $table instanceof Model ? $table->getTable() : $table;
            $rule = \Illuminate\Validation\Rule::unique($tableName, $column ?? $this->attribute);
            if (!is_null($value)) {
                $rule->ignore($value);
            }
            $this->rule['unique'] = $rule;
        }
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function nullable(bool $check = true, ?string $message = null): self
    {
        $this->rule['nullable'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function in(array|In $in, ?string $message = null): self
    {
        $this->rule['in'] = $in;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function notIn(array|NotIn $values, ?string $message = null): self
    {
        $this->rule['not_in'] = $values;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function enum(Enum $enum, ?string $message = null): self
    {
        $this->rule['enum'] = $enum;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function inEnum(string $enumClass, ?string $message = null): self
    {
        return $this->enum(new Enum($enumClass), $message);
    }

    public function notInEnum(string $enumClass, ?string $message = null): self
    {
        if (method_exists($enumClass, 'cases')) {
            $values = array_map(fn($case) => $case->value, $enumClass::cases());

            return $this->notIn($values, $message);
        }

        return $this;
    }

    public function sometimes(bool $check = true, ?string $message = null): self
    {
        $this->rule['sometimes'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function custom(mixed ...$rules): self
    {
        foreach ($rules as $rule) {
            $this->rule($rule);
        }

        return $this;
    }

    public function when(bool $condition, callable $callback, ?callable $defaultCallback = null): self
    {
        if ($condition) {
            $callback($this);
        } elseif ($defaultCallback) {
            $defaultCallback($this);
        }

        return $this;
    }

    public function missing(bool $check = true, ?string $message = null): self
    {
        $this->rule['missing'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function missingIf(string $field, string $value, ?string $message = null): self
    {
        $this->rule['missing_if'] = $field . ',' . $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function missingUnless(string $field, string $value, ?string $message = null): self
    {
        $this->rule['missing_unless'] = $field . ',' . $value;
        $this->setMessage('missing_unless', $message);

        return $this;
    }

    public function missingWith(array $values, ?string $message = null): self
    {
        $this->rule['missing_with'] = $values;
        $this->setMessage('missing_with', $message);

        return $this;
    }

    public function present(bool $check = true, ?string $message = null): self
    {
        $this->rule['present'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function presentIf(string $field, array $value, ?string $message = null): self
    {
        $this->rule['present_if'] = $field . ',' . implode(',', $value);
        $this->setMessage('present_if', $message);

        return $this;
    }

    public function presentUnless(string $field, array $value, ?string $message = null): self
    {
        $this->rule['present_unless'] = $field . ',' . implode(',', $value);
        $this->setMessage('present_unless', $message);

        return $this;
    }

    public function presentWith(array $value, ?string $message = null): self
    {
        $this->rule['present_with'] = $value;
        $this->setMessage('present_with', $message);

        return $this;
    }

    public function presentWithAll(array $value, ?string $message = null): self
    {
        $this->rule['present_with_all'] = $value;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function prohibited(bool $check = true, ?string $message = null): self
    {
        $this->rule['prohibited'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function prohibitedIf(string $field, array $value, ?string $message = null): self
    {
        $this->rule['prohibited_if'] = $field . ',' . implode(',', $value);
        $this->setMessage('prohibited_if', $message);

        return $this;
    }

    public function prohibitedUnless(string $field, array $value, ?string $message = null): self
    {
        $this->rule['prohibited_unless'] = $field . ',' . implode(',', $value);
        $this->setMessage('prohibited_unless', $message);

        return $this;
    }

    public function prohibits(array $fields, ?string $message = null): self
    {
        $this->rule['prohibits'] = $fields;
        $this->setMessage('prohibits', $message);

        return $this;
    }

    public function required(bool $check = true, ?string $message = null): self
    {
        $this->rule['required'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function requiredIf(string $field, array $value, ?string $message = null): self
    {
        $this->rule['required_if'] = $field . ',' . implode(',', $value);
        $this->setMessage('required_if', $message);

        return $this;
    }

    public function requiredIfAccepted(array $fields, ?string $message = null): self
    {
        $this->rule['required_if_accepted'] = $fields;
        $this->setMessage('required_if_accepted', $message);

        return $this;
    }

    public function requiredIfDeclined(array $field, ?string $message = null): self
    {
        $this->rule['required_if_declined'] = $field;
        $this->setMessage('required_if_declined', $message);

        return $this;
    }

    public function requiredWith(array $fields, ?string $message = null): self
    {
        $this->rule['required_with'] = $fields;
        $this->setMessage('required_with', $message);

        return $this;
    }

    public function requiredWithAll(array $fields, ?string $message = null): self
    {
        $this->rule['required_with_all'] = $fields;
        $this->setMessage('required_with_all', $message);

        return $this;
    }

    public function requiredWithout(array $fields, ?string $message = null): self
    {
        $this->rule['required_without'] = $fields;
        $this->setMessage('required_without', $message);

        return $this;
    }

    public function requiredWithoutAll(array $fields, ?string $message = null): self
    {
        $this->rule['required_without_all'] = $fields;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function requiredUnless(string $field, array $value, ?string $message = null): self
    {
        $this->rule['required_unless'] = $field . ',' . implode(',', $value);
        $this->setMessage('required_unless', $message);

        return $this;
    }

    public function requiredArrayKeys(array $keys, ?string $message = null): self
    {
        $this->rule['required_array_keys'] = $keys;
        $this->setMessage('required_array_keys', $message);

        return $this;
    }

    public function same(string $field, ?string $message = null): self
    {
        $this->rule['same'] = $field;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function declined(bool $check = true, ?string $message = null): self
    {
        $this->rule[__FUNCTION__] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function declinedIf(string $field, string $value, ?string $message = null): self
    {
        $this->rule['declined_if'] = $field . ',' . $value;
        $this->setMessage('declined_if', $message);

        return $this;
    }

    public function distinct(bool|string $check = true, ?string $message = null): self
    {
        $this->rule['distinct'] = $check;
        $this->setMessage(__FUNCTION__, $message);

        return $this;
    }

    public function distinctIgnoreCase(?string $message = null): self
    {
        $this->distinct('ignore_case');
        $this->setMessage('distinct', $message);

        return $this;
    }

    public function unless(bool $condition, callable $callback, ?callable $defaultCallback = null): self
    {
        return $this->when(!$condition, $callback, $defaultCallback);
    }

    protected function getDatabaseRule(): ?object
    {
        return $this->rule['unique'] ?? $this->rule['exists'] ?? null;
    }

    public function where(Closure|string $column, $value = null): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($rule = $this->getDatabaseRule()) {
            $rule->where($column, $value);
        }

        return $this;
    }

    public function whereNot(string $column, $value): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($rule = $this->getDatabaseRule()) {
            $rule->whereNot($column, $value);
        }

        return $this;
    }

    public function whereNull(string $column): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($rule = $this->getDatabaseRule()) {
            $rule->whereNull($column);
        }

        return $this;
    }

    public function whereNotNull(string $column): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($rule = $this->getDatabaseRule()) {
            $rule->whereNotNull($column);
        }

        return $this;
    }

    public function onlyTrashed(): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($rule = $this->getDatabaseRule()) {
            if (method_exists($rule, 'onlyTrashed')) {
                $rule->onlyTrashed();
            }
        }

        return $this;
    }

    public function withoutTrashed(): self
    {
        $this->compiledRules = null;
        $this->compiledMessages = null;
        if ($rule = $this->getDatabaseRule()) {
            if (method_exists($rule, 'withoutTrashed')) {
                $rule->withoutTrashed();
            }
        }

        return $this;
    }

    protected function prepareValidation(mixed $value): array
    {
        $attribute = $this->attribute !== '' ? $this->attribute : 'value';
        $rules = $this->getRule();
        $messages = $this->getMessage();

        if ($this->attribute === '') {
            $newRules = [];
            foreach ($rules as $k => $v) {
                $newKey = $k === '' ? 'value' : 'value.' . $k;
                $newRules[$newKey] = $v;
            }
            $rules = $newRules;

            $newMessages = [];
            foreach ($messages as $k => $v) {
                $newKey = $k === '' ? 'value' : 'value.' . $k;
                $newMessages[$newKey] = $v;
            }
            $messages = $newMessages;
        }

        return [
            'data' => [$attribute => $value],
            'rules' => $rules,
            'messages' => $messages,
            'attribute' => $attribute,
        ];
    }

    public function sanitize(callable $callback): static
    {
        $this->sanitizers[] = $callback;

        return $this;
    }

    public function transform(callable $callback): static
    {
        return $this->sanitize($callback);
    }

    public function default(mixed $value): static
    {
        return $this->sanitize(fn($val) => is_null($val) ? $value : $val);
    }

    public function applySanitizers(mixed $value): mixed
    {
        foreach ($this->sanitizers as $sanitizer) {
            $value = $sanitizer($value);
        }

        return $value;
    }

    public function getSanitizers(): array
    {
        $allSanitizers = [];
        if ($this->sanitizers !== []) {
            $allSanitizers[$this->attribute] = $this->sanitizers;
        }

        if ($this instanceof ArrayRuleBuilder) {
            if ($this->eachBuilder) {
                foreach ($this->eachBuilder->getSanitizers() as $subPath => $sanitizers) {
                    $newPath = ($this->attribute !== '' ? $this->attribute . '.*' : '*') . ($subPath !== '' ? '.' . $subPath : '');
                    $allSanitizers[$newPath] = $sanitizers;
                }
            }
            if ($this->childBuilders) {
                foreach ($this->childBuilders as $child) {
                    foreach ($child->getSanitizers() as $subPath => $sanitizers) {
                        $newPath = ($this->attribute !== '' ? $this->attribute . '.' : '') . $subPath;
                        $allSanitizers[$newPath] = $sanitizers;
                    }
                }
            }
        }

        return $allSanitizers;
    }

    public static function sanitizePath(mixed &$data, array $segments, callable $callback): void
    {
        if (empty($segments) || (count($segments) === 1 && $segments[0] === '')) {
            $data = $callback($data);

            return;
        }

        $segment = array_shift($segments);

        if ($segment === '*') {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (empty($segments)) {
                        $data[$key] = $callback($value);
                    } else {
                        self::sanitizePath($data[$key], $segments, $callback);
                    }
                }
            }
        } else {
            if (is_array($data) && Arr::has($data, $segment)) {
                $value = data_get($data, $segment);
                if (empty($segments)) {
                    $newValue = $callback($value);
                    data_set($data, $segment, $newValue);
                } else {
                    self::sanitizePath($value, $segments, $callback);
                    data_set($data, $segment, $value);
                }
            }
        }
    }

    public function __serialize(): array
    {
        $serializedSanitizers = [];
        foreach ($this->sanitizers as $sanitizer) {
            if ($sanitizer instanceof Closure) {
                $serializedSanitizers[] = new SerializableClosure($sanitizer);
            } else {
                $serializedSanitizers[] = $sanitizer;
            }
        }

        $data = [
            'attribute' => $this->attribute,
            'rule' => $this->rule,
            'messages' => $this->messages,
            'sanitizers' => $serializedSanitizers,
        ];

        if ($this instanceof ArrayRuleBuilder) {
            $data['eachBuilder'] = $this->eachBuilder;
            $data['childBuilders'] = $this->childBuilders;
        }

        return $data;
    }

    public function __unserialize(array $data): void
    {
        $this->attribute = $data['attribute'];
        $this->rule = $data['rule'];
        $this->messages = $data['messages'];
        $this->sanitizers = [];

        foreach ($data['sanitizers'] as $sanitizer) {
            if ($sanitizer instanceof SerializableClosure) {
                $this->sanitizers[] = $sanitizer->getClosure();
            } else {
                $this->sanitizers[] = $sanitizer;
            }
        }

        if ($this instanceof ArrayRuleBuilder) {
            $this->eachBuilder = $data['eachBuilder'] ?? null;
            $this->childBuilders = $data['childBuilders'] ?? null;
        }
    }

    public function validate(mixed $value): mixed
    {
        $prep = $this->prepareValidation($value);

        $validator = Validator::make(
            $prep['data'],
            $prep['rules'],
            $prep['messages']
        );

        $validated = $validator->validate();

        $sanitizers = $this->getSanitizers();
        $prepSanitizers = [];
        foreach ($sanitizers as $path => $callbacks) {
            if ($this->attribute === '') {
                $newPath = $path === '' ? 'value' : 'value.' . $path;
                $prepSanitizers[$newPath] = $callbacks;
            } else {
                $prepSanitizers[$path] = $callbacks;
            }
        }

        foreach ($prepSanitizers as $path => $callbacks) {
            $segments = explode('.', $path);
            self::sanitizePath($validated, $segments, function ($val) use ($callbacks) {
                foreach ($callbacks as $callback) {
                    $val = $callback($val);
                }

                return $val;
            });
        }

        return $validated[$prep['attribute']] ?? $value;
    }

    public function passes(mixed $value): bool
    {
        $prep = $this->prepareValidation($value);

        return Validator::make(
            $prep['data'],
            $prep['rules'],
            $prep['messages']
        )->passes();
    }

    public function fails(mixed $value): bool
    {
        return !$this->passes($value);
    }
}
