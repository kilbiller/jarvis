<?php

declare(strict_types=1);

namespace Jarvis;

use ArrayAccess;
use InvalidArgumentException;

class Validator {
	private const string DEFAULT_MESSAGE = '${key} is not valid.';

	/** @var list<array{key: string, rules: list<callable>, message: string, optional: bool}> */
	private array $rules = [];

	/** @var array<string, string> */
	private array $errors = [];

	/**
	 * Value at a dotted key, null when missing or when a parent is not an array
	 */
	private static function lookup(string $key, array|ArrayAccess $data): mixed {
		$value = $data;

		foreach (explode('.', $key) as $segment) {
			if ((!is_array($value) && !$value instanceof ArrayAccess) || !isset($value[$segment])) {
				return null;
			}
			$value = $value[$segment];
		}

		return $value;
	}

	private function addError(string $key, string $message): void {
		$this->errors[$key] = str_replace('${key}', $key, $message);
	}

	/**
	 * @param callable|list<callable> $rule
	 */
	private function pushRule(string $key, callable|array $rule, string $message, bool $optional): static {
		if ($key === '') {
			throw new InvalidArgumentException('Param key should not be empty.');
		}

		$this->rules[] = [
			'key' => $key,
			'rules' => is_callable($rule) ? [$rule] : array_values($rule),
			'message' => $message,
			'optional' => $optional,
		];

		return $this;
	}

	/**
	 * Add a rule that fails when the value is missing
	 *
	 * @param string $key Key of the value to validate, can be nested (i.e. 'user.firstname')
	 * @param callable|list<callable> $rule Validation function(s) receiving the value and the whole data
	 * @param string $message Error message, '${key}' is replaced by the key
	 */
	public function addRule(string $key, callable|array $rule, string $message = self::DEFAULT_MESSAGE): static {
		return $this->pushRule($key, $rule, $message, false);
	}

	/**
	 * Add a rule that is skipped when the value is missing
	 *
	 * @param string $key Key of the value to validate, can be nested (i.e. 'user.firstname')
	 * @param callable|list<callable> $rule Validation function(s) receiving the value and the whole data
	 * @param string $message Error message, '${key}' is replaced by the key
	 */
	public function addOptionalRule(string $key, callable|array $rule, string $message = self::DEFAULT_MESSAGE): static {
		return $this->pushRule($key, $rule, $message, true);
	}

	public function validate(array|ArrayAccess $data): bool {
		$this->errors = [];

		foreach ($this->rules as $rule) {
			$value = self::lookup($rule['key'], $data);

			// If rule is optional and value doesn't exist, then everything is fine
			if ($rule['optional'] && $value === null) {
				continue;
			}

			foreach ($rule['rules'] as $callable) {
				// We only keep the first error for a specific key
				if (array_key_exists($rule['key'], $this->errors)) {
					break;
				}

				if (!$callable($value, $data)) {
					$this->addError($rule['key'], $rule['message']);
				}
			}
		}

		return $this->errors === [];
	}

	/**
	 * Get validation errors indexed by key, validating $data first when given
	 *
	 * @return array<string, string>
	 */
	public function getErrors(array|ArrayAccess|null $data = null): array {
		if ($data !== null) {
			$this->validate($data);
		}

		return $this->errors;
	}
}
