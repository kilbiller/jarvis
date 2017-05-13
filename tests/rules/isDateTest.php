<?php

use Jarvis\Validator;
use function Jarvis\rules\isDate;

describe('isDate', function () {
	it('should validate', function () {
		$array = ['age' => '2012-02-24'];

		$validator = (new Validator())
		->addRule('age', isDate());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => ''];

		$validator = (new Validator())
		->addRule('age', isDate());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
