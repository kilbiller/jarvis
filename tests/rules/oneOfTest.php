<?php

use Jarvis\Validator;
use function Jarvis\rules\oneOf;
use function Jarvis\rules\noWhiteSpace;
use function Jarvis\rules\notEmpty;
use function Jarvis\rules\isNull;
use function Jarvis\rules\matchRegex;

describe('oneOf', function () {
	it('should validate', function () {
		$array = ['age' => 18];

		$f1 = function ($age) {return $age > 5;};

		$f2 = function ($age) {return $age > 20;};

		$validator = (new Validator())
			->addRule('age', oneOf($f1, $f2));

		expect($validator->validate($array))->toBe(true);
	});

	it('should fail to validate', function () {
		$array = ['age' => '18'];

		$f1 = function ($age) {return is_int($age);};

		$f2 = function ($age) {return $age > 20;};

		$validator = (new Validator())
			->addRule('age', oneOf($f1, $f2));

		expect($validator->validate($array))->toBe(false);
	});

	it('should work with any number of functions', function () {
		$array = ['age' => '18'];

		$f1 = function ($age) {return is_int($age);};

		$f2 = function ($age) {return $age > 20;};

		$f3 = function ($age) {return $age === '18';};

		$validator = (new Validator())
			->addRule('age', oneOf($f1, $f2, $f3));

		expect($validator->validate($array))->toBe(true);
	});

	it('should work our jarvis own rules', function () {
		$array = ['message' => ''];

		$validator = (new Validator())
			->addRule('message', oneOf(notEmpty(), noWhiteSpace()));

		expect($validator->validate($array))->toBe(true);
	});

	it('should work with complex conditions', function () {
		$array = ['url' => null];

		$validator = (new Validator())
			->addRule('url', oneOf(
				isNull(),
				matchRegex('/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/')
			));

		expect($validator->validate($array))->toBe(true);
	});
});
