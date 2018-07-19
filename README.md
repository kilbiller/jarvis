# Jarvis

[![CircleCI](https://circleci.com/gh/kilbiller/jarvis.svg?style=shield&circle-token=c8ce445694b31462f5a5f5e3de43125e6c7fd87b)](https://circleci.com/gh/kilbiller/jarvis)
[![codecov](https://codecov.io/gh/kilbiller/jarvis/branch/master/graph/badge.svg)](https://codecov.io/gh/kilbiller/jarvis)

A simple but powerful way to validate data in PHP.

Requires php >= 7.0

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
->addRule($key: string, $validationFunction: Function | [$validationFunction: Function], $message: string): void
$key: Can be nested (i.e. 'user.firstname').
$validationFunction: Can accept two parameter: the value corresponding to the key currently validated and the whole array. Should return a boolean.
$message: The error message, ${key} will be replaced by the key. Default message is : '${key} is not valid.'

->validate($dataToValidate: array): boolean

->getErrors(): array
->getErrors($dataToValidate: array): array
```

## Example

```php
use Jarvis\Validator;
use function Jarvis\rules\{between, lengthBetween, noWhiteSpace};

$data = ['age' => 45, 'firstname' => 'Jonathan', 'lastname' => 'Blow'];
$data2 = ['age' => 53, 'firstname' => 'Johnny', 'lastname' => 'Depp'];

$validator = (new Validator())
->addRule('age', between(15, 48));
->addRule('firstname', function ($firstname) {
	return $firstname === 'Jonathan';
}, '${key} is not Jonathan.');
->addRule('lastname', [noWhiteSpace(), lengthBetween(0, 50)]),

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

There is two way to apply muliple validation rules for a single key :

- Use an array of validation functions -> this way you can only use a single error message
- Call addRule multiple times with the same key -> this way you can be more precise with your error messages

All function are curried so you can built-in rules without any argument rather than '\Jarvis\rules\isNumber'.

```php
use Jarvis\Validator;
use function Jarvis\rules\isNumber;

$validator = (new Validator())
->addRule('age', isNumber());
```

### Todo

- Accept objects
- Add more default rules
