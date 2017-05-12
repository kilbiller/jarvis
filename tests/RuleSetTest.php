<?php

use Jarvis\RuleSet;

describe('RuleSet', function () {
	describe('addRule', function () {
        it('should throw an exception', function () {
			expect(function () {
				$validator = (new RuleSet())
				->addRule(null, function ($age) {
					return is_int($age);
				});
			})->toThrow();

			expect(function () {
				$validator = (new RuleSet())
				->addRule('', function ($age) {
					return is_int($age);
				});
			})->toThrow();

			expect(function () {
				$validator = (new RuleSet())
				->addRule('age', function ($age) {
					return is_int($age);
				}, 12);
			})->toThrow();
		});
	});
});
