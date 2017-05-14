<?php

use Jarvis\Validator;
use function Jarvis\rules\isJson;

describe('isJson', function () {
	it('should validate', function () {
		$array = ['age' => '[]'];

		$validator = (new Validator())
		->addRule('age', isJson());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => '[1, 2, 3, 4,]'];

		$validator = (new Validator())
		->addRule('age', isJson());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
