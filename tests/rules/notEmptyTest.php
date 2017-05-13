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
});
