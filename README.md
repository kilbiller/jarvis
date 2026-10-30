# Jarvis

[![CI](https://github.com/kilbiller/jarvis/actions/workflows/ci.yml/badge.svg)](https://github.com/kilbiller/jarvis/actions/workflows/ci.yml)

A simple but powerful way to validate data in PHP.

Requires php >= 8.3

## Why ?

I needed to be able to make complex validation logic easily and to get a nicely formatted array with the validation errors associated with their keys.
I couldn't find anything that satisfied these requirements so I made Jarvis.

## Features

- Fluent interface
- Treats custom validation logic as first class citizen. They're just functions !!
- Get validation errors in a simple array, each error is associated to the input name. Only the first error for each field is kept.

## Api

### Validator

```
->addRule(string $key, callable|array $rule, string $message = '${key} is not valid.'): static
$key: Can be nested (i.e. 'user.firstname'). Throws an InvalidArgumentException if empty.
$rule: A validation function or an array of validation functions. Each one receives the value corresponding to the key currently validated and the whole data, and should return a boolean.
$message: The error message, ${key} will be replaced by the key.

->addOptionalRule(string $key, callable|array $rule, string $message = '${key} is not valid.'): static
Same as addRule, but the rule is skipped when the value is missing (null).

->validate(array|ArrayAccess $data): bool

->getErrors(): array
->getErrors(array|ArrayAccess $data): array // validates $data first
```

## Example

```php
use Jarvis\Validator;
use function Jarvis\rules\{between, lengthBetween, noWhiteSpace};

$data = ['age' => 45, 'firstname' => 'Jonathan', 'lastname' => 'Blow'];
$data2 = ['age' => 53, 'firstname' => 'Johnny', 'lastname' => 'Depp'];

$validator = (new Validator())
	->addRule('age', between(15, 48))
	->addRule('firstname', fn ($firstname) => $firstname === 'Jonathan', '${key} is not Jonathan.')
	->addRule('lastname', [noWhiteSpace(), lengthBetween(0, 50)]);

$validator->validate($data); // -> true
$validator->getErrors(); // -> []

$validator->validate($data2); // -> false
$validator->getErrors(); // -> ['age' => 'age is not valid.', 'firstname' => 'firstname is not Jonathan.']
```

### Built-in rules

- between(min, max)
- isBoolean()
- isDate(format = 'Y-m-d')
- isJson()
- isNull()
- isNumber()
- isPositive()
- isUppercase()
- isString()
- lengthBetween(min, max)
- matchRegex(regex)
- notEmpty()
- noWhiteSpace()
- oneOf(...$functions)

### Pro tips

There are two ways to apply multiple validation rules for a single key :

- Use an array of validation functions -> this way you can only use a single error message
- Call addRule multiple times with the same key -> this way you can be more precise with your error messages

Built-in rules are functions returning a validation closure, so call them even when they take no argument: use `isNumber()` rather than `'\Jarvis\rules\isNumber'`.

```php
use Jarvis\Validator;
use function Jarvis\rules\isNumber;

$validator = (new Validator())
	->addRule('age', isNumber());
```

### Todo

- Accept objects
- Add more default rules
