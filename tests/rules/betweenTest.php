<?php

use Jarvis\Validator;
use function Jarvis\rules\between;

describe('between', function () {
	it('should validate', function () {
		$array = ['age' => 32];

		$validator = (new Validator())
		->addRule('age', between(15, 48));

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => 32];

		$validator = (new Validator())
		->addRule('age', between(34, 48));

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});

	it('should work by passing a string', function () {
		$array = ['age' => 32];

		$validator = (new Validator())
		->addRule('age', \Jarvis\rules\between(15, 48));

		expect($validator->validate($array))->toBe(true);
	});

	it('should be inclusive', function () {
		$validator = (new Validator())
		->addRule('age', \Jarvis\rules\between(0, 30));

		expect($validator->validate(['age' => 0]))->toBe(true);
		expect($validator->validate(['age' => 30]))->toBe(true);
	});

});
