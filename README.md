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
->addRule($key: string, $validationFunction: Function, $message: string): void
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
use function Jarvis\rules\between;

$data = ['age' => 45, 'firstname' => 'Jonathan', 'lastname' => 'Blow'];
$data2 = ['age' => 53, 'firstname' => 'Johnny', 'lastname' => 'Depp'];

$validator = (new Validator())
->addRule('age', between(15, 48));
->addRule('firstname', function ($firstname) {
	return $firstname === 'Jonathan';
}, '${key} is not Jonathan.');

$validator->validate($data); // -> true
$validator->getErrors(); // -> []

$validator->validate($data2); // -> false
$validator->getErrors(); // -> ['age' => 'age is not valid.', 'firstname' => 'firstname is not Jonathan.']
```

### Todo

- Accept objects
- Add more default rules
