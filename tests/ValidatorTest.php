<?php

use Jarvis\Validator;
use Jarvis\RuleSet;

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

			expect(function () {
				$validator = (new Validator())
				->addRule('age', function ($age) {
					return is_int($age);
				}, 12);
			})->toThrow();
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

        it('should fail to validate', function () {
			$array = ['age' => 34, 'godson' => ['firstname' => 'Jonathan']];

			$ruleset = (new RuleSet())
            ->addRule('zombie', function ($age) {
                return is_int($age);
            });

            $validator = new Validator($ruleset);

            expect($validator->validate($array))->toBe(false);
            expect($validator->getErrors())->toBe(['zombie' => 'zombie is not valid.']);
        });
    });
});
