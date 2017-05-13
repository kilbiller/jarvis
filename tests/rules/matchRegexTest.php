<?php

use Jarvis\Validator;
use function Jarvis\rules\matchRegex;

describe('matchRegex', function () {
	it('should validate', function () {
		$array = ['age' => 'co_365465'];

		$validator = (new Validator())
		->addRule('age', matchRegex('/^co_*/'));

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => 'oliver'];

		$validator = (new Validator())
		->addRule('age', matchRegex('/^co_*/'));

		expect($validator->validate($array))->toBe(false);
		expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
	});
});
