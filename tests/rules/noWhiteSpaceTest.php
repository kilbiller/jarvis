<?php

use Jarvis\Validator;
use function Jarvis\rules\noWhiteSpace;

describe('noWhiteSpace', function () {
	it('should validate', function () {
		$array = ['age' => 'patrick'];

		$validator = (new Validator())
		->addRule('age', noWhiteSpace());

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => 'I am an only child.'];

		$validator = (new Validator())
		->addRule('age', noWhiteSpace());

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
