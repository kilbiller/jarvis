# Jarvis

[![CircleCI](https://circleci.com/gh/kilbiller/jarvis.svg?style=shield&circle-token=aefb7d59b512e8ea98b5e24f931f84aa40f4f083)](https://circleci.com/gh/kilbiller/jarvis)
[![codecov](https://codecov.io/gh/kilbiller/jarvis/branch/master/graph/badge.svg)](https://codecov.io/gh/kilbiller/jarvis)

A simple but powerful way to validate data in PHP.

Requires php >= 7.0

new validator()
	->key('keys.can.be.nested', function validationCallback($value, $THE_ARRAY), 'Validation message with ${key} that substitute with key name')
	->key('keys.can.be.nested', 'validationRule' OR Enum::validationRule, 'Validation message with ${key} that substitute with key name' But has also a default message)
	->validate(THE_ARRAY)

	= An array with all the messages i.e. [
		'key1' => 'key1 is not a string',
		'key2' => 'key2 is not an int'
	] // Only 1 error per key other are skipped

OR

$schema = new validationSchema()
	->key('keys.can.be.nested', function validationCallback($value, $THE_ARRAY), 'Validation message with ${key} that substitute with key name')
	->key('keys.can.be.nested', 'validationRule' OR Enum::validationRule, 'Validation message with ${key} that substitute with key name' But has also a default message)

new validator($schema)->validate(THE_ARRAY)

- avoid exceptions
- unit test

Evolution
- accept objects
- more default rules
