<?php

use Jarvis\Validator;
use function Jarvis\rules\isBoolean;

describe('isBoolean', function () {
	it('should validate', function () {
		$array = ['age' => true];

		$validator = (new Validator())
		->addRule('age', isBoolean());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => ''];

		$validator = (new Validator())
		->addRule('age', isBoolean());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
