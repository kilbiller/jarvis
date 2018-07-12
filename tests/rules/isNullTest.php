<?php

use Jarvis\Validator;
use function Jarvis\rules\isNull;

describe('isNull', function () {
	it('should validate', function () {
		$array = ['age' => null];

		$validator = (new Validator())
		->addRule('age', isNull());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => ''];

		$validator = (new Validator())
		->addRule('age', isNull());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
