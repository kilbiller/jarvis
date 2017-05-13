<?php

use Jarvis\Validator;
use function Jarvis\rules\lengthBetween;

describe('lengthBetween', function () {
	it('should validate', function () {
		$array = ['age' => 'CODE01'];

		$validator = (new Validator())
		->addRule('age', lengthBetween(0, 15));

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => 'code02'];

		$validator = (new Validator())
		->addRule('age', lengthBetween(0, 4));

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
