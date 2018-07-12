<?php

use Jarvis\Validator;
use Jarvis\RuleSet;
use function Jarvis\rules\{isNumber, between};

describe('Validator', function () {
	describe('addRule', function () {
        it('should throw an exception', function () {
			expect(function () {
				$validator = (new Validator())
				->addRule(null, function ($age) {
					return is_int($age);
				});
			})->toThrow();

			expect(function () {
				$validator = (new Validator())
				->addRule('', function ($age) {
					return is_int($age);
				});
			})->toThrow();
		});

		it('should fail when key is not set', function () {
			$validator = (new Validator())
				->addRule('age', function ($age) {
					return is_int($age);
				});

			$data = ['godson' => ['firstname' => 'Jonathan']];
			
			expect($validator->validate($data))->toBe(false);
			expect($validator->getErrors())->toBe(['age' => 'age is not valid.']);
		});
	});

	describe('addOptionalRule', function () {
        it('should throw an exception', function () {
			expect(function () {
				$validator = (new Validator())
				->addOptionalRule(null, function ($age) {
					return is_int($age);
				});
			})->toThrow();

			expect(function () {
				$validator = (new Validator())
				->addOptionalRule('', function ($age) {
					return is_int($age);
				});
			})->toThrow();
		});

		it('should pass when key is not set', function () {
			$validator = (new Validator())
				->addOptionalRule('age', function ($age) {
					return is_int($age);
				});

			$data = ['godson' => ['firstname' => 'Jonathan']];
			
			expect($validator->validate($data))->toBe(true);
			expect($validator->getErrors())->toBe([]);
		});
	});

    describe('validate', function () {
        it('should validate correctly', function () {
            $array = ['age' => 34, 'godson' => ['firstname' => 'Jonathan']];

            $validator = (new Validator())
            ->addRule('age', function ($age) {
                return is_int($age);
            })
            ->addRule('godson.firstname', function ($firstname) {
                return $firstname === 'Jonathan';
            }, '${key} is not Jonathan.');

            expect($validator->validate($array))->toBe(true);
            expect($validator->getErrors())->toBe([]);
        });

		it('should validate with multiple rules', function () {
            $array = ['age' => 34, 'godson' => ['firstname' => 'Jonathan']];

            $validator = (new Validator())
			->addRule('age', [isNumber(), between(0, 100)])
			->addOptionalRule('height', [isNumber(), between(0, 100)])
            ->addRule('godson.firstname', function ($firstname) {
                return $firstname === 'Jonathan';
            }, '${key} is not Jonathan.');

            expect($validator->validate($array))->toBe(true);
            expect($validator->getErrors())->toBe([]);
        });

        it('should fail to validate', function () {
			$array = ['age' => 34, 'godson' => ['firstname' => 'Jonathan']];

			$validator = (new Validator())
            ->addRule('zombie', function ($age) {
                return is_int($age);
            });

            expect($validator->validate($array))->toBe(false);
            expect($validator->getErrors())->toBe(['zombie' => 'zombie is not valid.']);
        });

		it('should fail to validate with multiple rules', function () {
            $array = ['age' => 34, 'godson' => ['firstname' => 'Jonathan']];

            $validator = (new Validator())
            ->addRule('age', [isNumber(), between(20, 100)])
            ->addOptionalRule('godson.firstname', function ($firstname) {
                return $firstname !== 'Jonathan';
            }, '${key} is Jonathan.');

            expect($validator->validate($array))->toBe(false);
        });
    });

	describe('getErrors', function () {
        it('should also validate data if data is passed as first argument', function () {
			$array = ['age' => 34, 'godson' => ['firstname' => 'Jonathan']];

			$validator = (new Validator())
            ->addRule('zombie', function ($age) {
                return is_int($age);
            });

            expect($validator->getErrors($array))->toBe(['zombie' => 'zombie is not valid.']);
		});
	});
});
