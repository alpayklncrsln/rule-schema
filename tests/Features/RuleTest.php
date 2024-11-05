<?php

use Alpayklncrsln\RuleSchema\Enums\FileMime;
use Alpayklncrsln\RuleSchema\Rule;

test('get attribute of rule ', function () {
    $rule = Rule::make('name');

    expect($rule)->toBeInstanceOf(Rule::class)
        ->and($rule)
        ->getAttribute()
        ->toBe('name')
        ->toBeString();
});

test('rule accepted check', function () {
    $rule = Rule::make('name')->accepted()
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('accepted');
});

test('active_url ', function () {
    $rule = Rule::make('name')->activeUrl()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('active_url');
});

test('rule acceptedIf check', function () {
    $rule = Rule::make('name')->acceptedIf('field', 'value')
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('accepted_if');
});

test('rule after check', function () {
    $rule = Rule::make('name')->after('2020-01-01')
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('after');
});

test('rule afterDate check', function () {
    $rule = Rule::make('name')->afterDate('2020-01-01')
        ->getRule();
    expect($rule['name'])
        ->toBeArray()
        ->toBeArray('after')
        ->toBeArray('date');
});

test('rule afterDateYesterday check', function () {
    $rule = Rule::make('date')->afterDateYesterday()->getRule();

    expect($rule['date'])
        ->toBeArray()
        ->toBeArray('after:yesterday')
        ->toBeArray('date');
});

test('rule afterDateTomorrow check', function () {
    $rule = Rule::make('date')->afterDateTomorrow()->getRule();

    expect($rule['date'])
        ->toBeArray()
        ->toBeArray('after:tomorrow')
        ->toBeArray('date');
});

test('rule alpha check', function () {
    $rule = Rule::make('name')->alpha('ascii')
        ->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('alpha:ascii');
});

test('rule alphaNumeric check', function (string $value) {
    $rule = Rule::make('name')->alphaNumeric($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('alpha_num:'.$value);
})->with([
    'ascii',
]);

test('rule alphaDash check', function (string $value) {
    $rule = Rule::make('name')->alphaDash($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('alpha_dash:'.$value);
})->with([
    'ascii',
]);

test('array check', function () {
    $rule = Rule::make('content')->array()->getRule();
    expect($rule['content'])->toBeArray()
        ->toBeArray('array');
});
test('rule bail', function () {
    $rule = Rule::make('name')->bail()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('bail');
});

test('rule before', function () {
    $rule = Rule::make('date')->before('2020-01-01')->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before');
});

test('rule beforeDate', function () {
    $rule = Rule::make('date')->beforeDate('2020-01-01')->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before')
        ->toBeArray('date');
});

test('rule beforeDateYesterday', function () {
    $rule = Rule::make('date')->beforeDateYesterday()->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before:yesterday')
        ->toBeArray('date');
});

test('rule beforeDateTomorrow', function () {
    $rule = Rule::make('date')->beforeDateTomorrow()->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('before:tomorrow')
        ->toBeArray('date');
});

test('rule between', function () {
    $rule = Rule::make('name')->between(1, 10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('between:1,10');
});

test('rule boolean', function () {
    $rule = Rule::make('is_admin')->boolean()->getRule();
    expect($rule['is_admin'])->toBeArray()
        ->toBeArray('boolean');
});

test('rule confirmed', function () {
    $rule = Rule::make('password')->confirmed()->getRule();
    expect($rule['password'])->toBeArray()
        ->toBeArray('confirmed');
});

test('contains', function (string $value) {
    $rule = Rule::make('name')->contains($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('contains:'.$value);
})->with([
    'test',
    'test123',
    'test 123',
    'test 123 test',
]);

test('currentPassword', function (bool|string $guard) {
    $rule = Rule::make('name')->currentPassword($guard)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('current_password:'.$guard);
})->with([
    'web',
    'api',
    'admin',
    'user',
    true,
]);

test('date', function () {
    $rule = Rule::make('date')->date()->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('date');
});
test('dateEquals', function () {
    $rule = Rule::make('date')->dateEquals('2020-01-01')->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('date_equals:2020-01-01');
});

test('dateFormat', function (string $value) {
    $rule = Rule::make('date')->dateFormat($value)->getRule();
    expect($rule['date'])->toBeArray()
        ->toBeArray('date_format:'.$value);
})->with([
    'Y-m-d',
    'Y-m-d H:i',
    'Y-m-d H:i:s',
    'Y-m-d H:i:s.u',
    'Y-m-d H:i:s P',
    'Y-m-d H:i:s T',
    'Y-m-d H:i:s O',
    'Y-m-d H:i:s e',
    'Y-m-d H:i:s c',
    'Y-m-d H:i:s v',
    'Y-m-d H:i:s x',
    'Y-m-d H:i:s u',
]);
test('decimal', function ($min, $max) {
    $rule = Rule::make('price')->decimal($min, $max)->getRule();
    expect($rule['price'])->toBeArray()
        ->toBeArray('decimal:'.$min.($max ? ','.$max : ''));
})->with([
    [2, null],
    [2, 3],
    [2, null],
]);

test('declined', function () {
    $rule = Rule::make('name')->declined()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('declined');
});

test('declinedIf', function (string $field, string $value) {
    $rule = Rule::make('name')->declinedIf($field, $value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('declined_if:'.$field.','.$value);
})->with([
    ['is_admin', 'true'],
    ['is_admin', 'false'],
    ['email', 'admin@example.com'],
]);

test('different', function (string $field) {
    $rule = Rule::make('name')->different($field)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('different:'.$field);
})->with([
    'email',
    'password',
]);

test('digits', function () {
    $rule = Rule::make('name')->digits(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('digits:10');
});

test('digitsBetween', function () {
    $rule = Rule::make('name')->digitsBetween(1, 10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('digits_between:1,10');
});

test('demensions', function (string $value) {
    $rule = Rule::make('name')->demensions($value)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('demensions:'.$value);
})->with([
    'width',
    'height',
]);

test('demensionsImageWidthHeight', function (int $width, int $height) {
    $rule = Rule::make('name')->demensionsImageWidthHeight($width, $height)->getRule();
    expect($rule['name'])->toBeArray('demensions:width'.$width.',height:'.$height);

})->with([
    [100, 100],
    [200, 200],
]);

test('distinct', function () {
    $rule = Rule::make('name')->distinct()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('distinct');
});

test('distinctIgnoreCase', function () {
    $rule = Rule::make('name')->distinctIgnoreCase()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('distinct:ignore_case');
});

test('doesntStartWith', function () {
    $rule = Rule::make('name')->doesntStartWith('admin')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('doesnt_start_with:admin');
});

test('doesntEndWith', function () {
    $rule = Rule::make('name')->doesntEndWith('admin')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('doesnt_end_with:admin');
});

test('email', function () {
    $rule = Rule::make('name')->email()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('email');
});

test('endsWith', function () {
    $rule = Rule::make('name')->endsWith('admin')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('ends_with:admin');
});

test('exists', function () {
    $rule = Rule::make('name')->exists('users')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('exists:users');
});

test('hexColor', function () {
    $rule = Rule::make('name')->hexColor()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('hex_color');
});

test('string', function () {
    $rule = Rule::make('name')->string()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('string');
});

test('max', function () {
    $rule = Rule::make('name')->max(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('max:10');
});

test('min', function () {
    $rule = Rule::make('name')->min(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('min:10');
});

test('array', function () {
    $rule = Rule::make('name')->array()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('array');
});

test('in', function () {
    $rule = Rule::make('name')->in(['admin', 'user'])->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('in:admin,user');
});

test('unique', function () {
    $rule = Rule::make('name')->unique('users')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('unique:users');
});
test('nullable', function () {
    $rule = Rule::make('name')->nullable()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('nullable');
});

test('image', function () {
    $rule = Rule::make('image')->image()->getRule();
    expect($rule['image'])->toBeArray()
        ->toBeArray('image');
});

test('file', function () {
    $rule = Rule::make('file')->file()->getRule();
    expect($rule['file'])->toBeArray()
        ->toBeArray('file');
});

test('regex', function () {
    $rule = Rule::make('name')->regex('/^admin$/')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('regex:/^admin$/');
});

test('notRegex', function () {
    $rule = Rule::make('name')->notRegex('/^admin$/')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('nopt_regex:/^admin$/');
});

test('gt', function () {
    $rule = Rule::make('name')->gt(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('gt:10');
});

test('gte', function () {
    $rule = Rule::make('name')->gte(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('gte:10');
});

test('lt', function () {
    $rule = Rule::make('name')->lt(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('lt:10');
});

test('lte', function () {
    $rule = Rule::make('name')->lte(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('lte:10');
});
test('json', function () {
    $rule = Rule::make('name')->json()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('json');
});

test('ip', function () {
    $rule = Rule::make('ip')->ip()->getRule();
    expect($rule['ip'])->toBeArray()
        ->toBeArray('ip');
});

test('ipv4', function () {
    $rule = Rule::make('ip')->ipv4()->getRule();
    expect($rule['ip'])->toBeArray()
        ->toBeArray('ipv4');
});

test('ipv6', function () {
    $rule = Rule::make('ip')->ipv6()->getRule();
    expect($rule['ip'])->toBeArray()
        ->toBeArray('ipv6');
});

test('mimes string', function () {
    $rule = Rule::make('image')->mimes('png', 'jpg')->getRule();
    expect($rule['image'])->toBeArray()
        ->toBeArray('mimes:png,jpg');
});

test('mimes enum', function (FileMime $fileMime) {
    $rule = Rule::make('image')->mimes($fileMime)->getRule();
    expect($rule['image'])->toBeArray()
        ->toBeArray('mimes:'.$fileMime->value);
})->with(FileMime::cases());

test('mimetypes string', function () {
    $rule = Rule::make('image')->mimetypes('image/png', 'image/jpeg')->getRule();
    expect($rule['image'])->toBeArray()
        ->toBeArray('mimetypes:image/png,image/jpeg');
});

test('mimetypes enum', function (FileMime $fileMime) {
    $rule = Rule::make('image')->mimetypes($fileMime)->getRule();
    expect($rule['image'])->toBeArray()
        ->toBeArray('mimetypes:'.$fileMime->type());
})->with(FileMime::cases());

test('enum', function () {})->todo();

test('mimeAndMimetypes', function (FileMime $fileMime) {
    $rule = Rule::make('image')->mimeAndMimetypes()->getRule();
    expect($rule['image'])->toBeArray()
        ->toBeArray('mimetypes:'.$fileMime->type())
        ->toBeArray('mimes:'.$fileMime->value);
})->with(FileMime::cases());

test('maxDigits', function () {
    $rule = Rule::make('name')->maxDigits(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('max_digits:10');
});

test('minDigits', function () {
    $rule = Rule::make('name')->minDigits(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('min_digits:10');
});

test('multipleOf', function () {
    $rule = Rule::make('name')->multipleOf(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('multiple_of:10');
});

test('missing', function () {
    $rule = Rule::make('name')->missing()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('missing');
});

test('missingIf', function () {
    $rule = Rule::make('name')->missingIf('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('missing_if:field,value');
});

test('missingUnless', function () {
    $rule = Rule::make('name')->missingUnless('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('missing_unless:field,value');
});

test('missingWith', function () {
    $rule = Rule::make('name')->missingWith('value', 'value1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('missing_with:value,value1');
});

test('notIn', function () {
    $rule = Rule::make('name')->notIn('value1', 'value2')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('not_in:value1,value2');
});

test('present', function () {
    $rule = Rule::make('name')->present()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('present');
});
test('presentIf', function () {
    $rule = Rule::make('name')->presentIf('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('present_if:field,value');
});

test('presentUnless', function () {
    $rule = Rule::make('name')->presentUnless('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('present_unless:field,value');
});

test('presentWith', function () {
    $rule = Rule::make('name')->presentWith('value', 'value1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('present_with:value,value1');
});

test('presentWithAll', function () {
    $rule = Rule::make('name')->presentWithAll('value', 'value1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('present_with_all:value,value1');
});
test('prohibited', function () {
    $rule = Rule::make('name')->prohibited()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('prohibited');
});

test('prohibitedIf', function () {
    $rule = Rule::make('name')->prohibitedIf('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('prohibited_if:field,value');
});

test('prohibitedUnless', function () {
    $rule = Rule::make('name')->prohibitedUnless('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('prohibited_unless:field,value');
});

test('prohibits', function () {
    $rule = Rule::make('name')->prohibits('value1', 'value2')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('prohibits:value1,value2');
});

test('required', function () {
    $rule = Rule::make('name')->required()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('required');
});

test('requiredIf', function () {
    $rule = Rule::make('name')->requiredIf('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('required_if:field,value');
});

test('requiredUnless', function () {
    $rule = Rule::make('name')->requiredUnless('field', 'value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('required_unless:field,value');
});

test('requiredWith', function () {
    $rule = Rule::make('name')->requiredWith('value', 'value1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('required_with:value,value1');
});

test('requiredWithAll', function () {
    $rule = Rule::make('name')->requiredWithAll('value', 'value1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('required_with_all:value,value1');
});

test('same', function () {
    $rule = Rule::make('name')->same('value1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('same:value1');
});

test('size', function () {
    $rule = Rule::make('name')->size(10)->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('size:10');
});

test('requiredArrayKeys', function () {
    $rule = Rule::make('name')->requiredArrayKeys('key', 'key1')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('required_array_keys:key,key1');
});

test('startsWith', function () {
    $rule = Rule::make('name')->startsWith('value')->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('starts_with:value');
});
test('timezone', function () {
    $rule = Rule::make('name')->timezone()->getRule();
    expect($rule['name'])->toBeArray()
        ->toBeArray('timezone:all');
});

test('url', function () {
    $rule = Rule::make('name')->url()->getRule();
    expect($rule['name'])->toBeArray('url');
});

test('urlHttp', function () {
    $rule = Rule::make('name')->urlHttp()->getRule();
    expect($rule['name'])->toBeArray('url:http');
});

test('urlHttps', function () {
    $rule = Rule::make('name')->urlHttps()->getRule();
    expect($rule['name'])->toBeArray('url:https');
});

test('urlHttpAndHttps', function () {
    $rule = Rule::make('name')->urlHttpAndHttps()->getRule();
    expect($rule['name'])->toBeArray('url:http,https');
});

test('uuid', function () {
    $rule = Rule::make('name')->uuid()->getRule();
    expect($rule['name'])->toBeArray('uuids');
});

test('ulid', function () {
    $rule = Rule::make('name')->ulid()->getRule();
    expect($rule['name'])->toBeArray('ulid');
});
