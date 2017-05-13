<?php

use Jarvis\Validator;
use function Jarvis\rules\isNumber;

describe('isNumber', function () {
	it('should validate', function () {
		$array = ['age' => 48];

		$validator = (new Validator())
		->addRule('age', isNumber());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => ''];

		$validator = (new Validator())
		->addRule('age', isNumber());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
