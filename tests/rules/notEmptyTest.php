<?php

use Jarvis\Validator;
use function Jarvis\rules\notEmpty;

describe('notEmpty', function () {
	it('should validate', function () {
		$array = ['age' => 32];

		$validator = (new Validator())
		->addRule('age', notEmpty());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => ''];

		$validator = (new Validator())
		->addRule('age', notEmpty());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});

	it('should count NAN as not empty and 0.0 as empty', function () {
		$validator = (new Validator())
		->addRule('age', notEmpty());

		expect($validator->validate(['age' => NAN]))->toBe(true);
		expect($validator->validate(['age' => 0.0]))->toBe(false);
		expect($validator->validate(['age' => -0.0]))->toBe(false);
	});

	it('should count false as not empty', function () {
		$array = ['age' => false];

		$validator = (new Validator())
		->addRule('age', notEmpty());

		expect($validator->validate($array))->toBe(true);
	});
});
