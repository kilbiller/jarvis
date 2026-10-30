<?php

declare(strict_types=1);

require_once __DIR__ . '/support/generators.php';

use Jarvis\Validator;
use Random\Randomizer;

use function Jarvis\rules\{between, isBoolean, isDate, isJson, isNull, isNumber, isPositive, isString, isUppercase, lengthBetween, matchRegex, noWhiteSpace, notEmpty, oneOf};
use function Jarvis\Tests\{anyData, anyValue, iterations, lookup, pick, randomizer, seed, show};

/**
 * @return array<string, callable>
 */
function builtInRules(): array {
	return [
		'between(0, 10)' => between(0, 10),
		'between(-1.5, 2.5)' => between(-1.5, 2.5),
		'isBoolean()' => isBoolean(),
		'isDate()' => isDate(),
		"isDate('d/m/Y H:i')" => isDate('d/m/Y H:i'),
		'isJson()' => isJson(),
		'isNull()' => isNull(),
		'isNumber()' => isNumber(),
		'isPositive()' => isPositive(),
		'isString()' => isString(),
		'isUppercase()' => isUppercase(),
		'lengthBetween(0, 5)' => lengthBetween(0, 5),
		'lengthBetween(null, 5)' => lengthBetween(null, 5),
		"matchRegex('/^a/u')" => matchRegex('/^a/u'),
		'noWhiteSpace()' => noWhiteSpace(),
		'notEmpty()' => notEmpty(),
		'oneOf(isNull(), isString())' => oneOf(isNull(), isString()),
	];
}

const KEYS = ['a', 'b', 'a.b', 'c.d.e'];

/**
 * Random rules to register on a validator
 *
 * @return list<array{key: string, rules: list<callable>, message: string, optional: bool}>
 */
function anySpecs(Randomizer $r): array {
	$rules = [...array_values(builtInRules()), fn (): bool => true, fn (): bool => false];
	$specs = [];

	for ($i = $r->getInt(0, 6); $i > 0; $i--) {
		$specs[] = [
			'key' => pick($r, KEYS),
			'rules' => array_map(fn () => pick($r, $rules), range(0, $r->getInt(0, 2))),
			'message' => '#' . count($specs) . ' ${key}',
			'optional' => $r->getInt(0, 1) === 1,
		];
	}

	return $specs;
}

function buildValidator(array $specs): Validator {
	$validator = new Validator();

	foreach ($specs as $spec) {
		$rule = count($spec['rules']) === 1 ? $spec['rules'][0] : $spec['rules'];
		$spec['optional']
			? $validator->addOptionalRule($spec['key'], $rule, $spec['message'])
			: $validator->addRule($spec['key'], $rule, $spec['message']);
	}

	return $validator;
}

/**
 * Reference model: for each key, the message of the first applicable rule that fails
 */
function expectedErrors(array $specs, array|ArrayAccess $data): array {
	$errors = [];

	foreach ($specs as $spec) {
		$key = $spec['key'];
		$value = lookup($key, $data);

		if (array_key_exists($key, $errors) || ($spec['optional'] && $value === null)) {
			continue;
		}

		foreach ($spec['rules'] as $rule) {
			if (!$rule($value, $data)) {
				$errors[$key] = str_replace('${key}', $key, $spec['message']);
				break;
			}
		}
	}

	return $errors;
}

/**
 * Run $check for many random cases and collect failure descriptions
 *
 * @param callable(Randomizer): ?string $check Returns a description of the failure, null on success
 * @return list<string>
 */
function forAll(callable $check): array {
	$seed = seed();
	$r = randomizer($seed);
	$failures = [];

	set_error_handler(function (int $severity, string $message): never {
		throw new ErrorException($message, 0, $severity);
	});

	try {
		for ($i = 0; $i < iterations() && count($failures) < 5; $i++) {
			try {
				$failure = $check($r);
			} catch (Throwable $e) {
				$failure = get_class($e) . ': ' . $e->getMessage();
			}

			if ($failure !== null) {
				$failures[] = "JARVIS_SEED=$seed, case $i: $failure";
			}
		}
	} finally {
		restore_error_handler();
	}

	return $failures;
}

describe('Properties', function () {
	describe('built-in rules', function () {
		it('should return a bool for any value without errors or warnings', function () {
			$failures = forAll(function (Randomizer $r): ?string {
				$value = anyValue($r);

				foreach (builtInRules() as $name => $rule) {
					try {
						$rule($value, ['key' => $value]);
					} catch (Throwable $e) {
						return "$name(" . show($value) . ') threw ' . get_class($e) . ': ' . $e->getMessage();
					}
				}

				return null;
			});

			expect($failures)->toBe([]);
		});
	});

	describe('Validator', function () {
		it('should return true from validate exactly when there are no errors', function () {
			$failures = forAll(function (Randomizer $r): ?string {
				$data = anyData($r, KEYS);
				$validator = buildValidator(anySpecs($r));
				$valid = $validator->validate($data);

				if ($valid !== ($validator->getErrors() === [])) {
					return 'validate() returned ' . var_export($valid, true) . ' with errors ' . show($validator->getErrors());
				}

				return $validator->getErrors($data) === $validator->getErrors() ? null : 'getErrors($data) differs from validate($data)';
			});

			expect($failures)->toBe([]);
		});

		it('should keep the first error of each key', function () {
			$failures = forAll(function (Randomizer $r): ?string {
				$data = anyData($r, KEYS);
				$specs = anySpecs($r);
				$validator = buildValidator($specs);
				$validator->validate($data);

				$expected = expectedErrors($specs, $data);
				$actual = $validator->getErrors();

				return $actual === $expected ? null : 'expected ' . show($expected) . ', got ' . show($actual) . ' for ' . show($data);
			});

			expect($failures)->toBe([]);
		});

		it('should not report missing values for keys with only optional rules', function () {
			$failures = forAll(function (Randomizer $r): ?string {
				$data = anyData($r, KEYS);
				$specs = anySpecs($r);
				$validator = buildValidator($specs);
				$validator->validate($data);

				foreach (array_keys($validator->getErrors()) as $key) {
					$requiredSpecs = array_filter($specs, fn ($spec) => $spec['key'] === $key && !$spec['optional']);

					if ($requiredSpecs === [] && lookup($key, $data) === null) {
						return "missing optional key '$key' reported in " . show($data);
					}
				}

				return null;
			});

			expect($failures)->toBe([]);
		});

		it('should not depend on previous validations', function () {
			$failures = forAll(function (Randomizer $r): ?string {
				$specs = anySpecs($r);
				$previous = anyData($r, KEYS);
				$data = anyData($r, KEYS);

				$reused = buildValidator($specs);
				$reused->validate($previous);
				$reusedValid = $reused->validate($data);

				$fresh = buildValidator($specs);
				$freshValid = $fresh->validate($data);

				if ($reusedValid !== $freshValid || $reused->getErrors() !== $fresh->getErrors()) {
					return 'validating ' . show($previous) . ' first changed the result for ' . show($data);
				}

				return null;
			});

			expect($failures)->toBe([]);
		});
	});
});
