<?php

use Jarvis\Validator;
use function Jarvis\rules\isPositive;

describe('isPositive', function () {
	it('should validate', function () {
		$array = ['age' => 150];

		$validator = (new Validator())
		->addRule('age', isPositive());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => -15];

		$validator = (new Validator())
		->addRule('age', isPositive());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});

	it('should fail to validate non numeric values', function () {
		$validator = (new Validator())
		->addRule('age', isPositive());

		expect($validator->validate(['age' => [1]]))->toBe(false);
		expect((new Validator())->addRule('age', isPositive())->validate(['age' => 'abc']))->toBe(false);
	});
});
