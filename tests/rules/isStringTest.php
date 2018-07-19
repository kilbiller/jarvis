<?php

use Jarvis\Validator;
use function Jarvis\rules\isString;

describe('isString', function () {
	it('should validate', function () {
		$array = ['age' => 'i am a string'];

		$validator = (new Validator())
		->addRule('age', isString());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => 32];

		$validator = (new Validator())
		->addRule('age', isString());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
