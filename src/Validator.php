<?php

namespace Jarvis;

use function fphp\prop;
use Exception;

class Validator {
	private $rules = [];
	private $errors = [];

	private function addError($key, $message) {
		$message = str_replace ('${key}', $key, $message);
		$this->errors[$key] = $message;
	}

	public function addRule(string $key, $rule, string $message = '${key} is not valid.') {
		if (empty($key)) {
			throw new Exception('Param key should not be empty.');
		}

		$this->rules[] = [
			'key' => $key,
			'rule' => $rule,
			'message' => $message,
			'options' => [
				'optional' => false,
			],
		];

		return $this;
	}

	public function addOptionalRule(string $key, $rule, string $message = '${key} is not valid.') {
		if (empty($key)) {
			throw new Exception('Param key should not be empty.');
		}

		$this->rules[] = [
			'key' => $key,
			'rule' => $rule,
			'message' => $message,
			'options' => [
				'optional' => true,
			],
		];

		return $this;
	}

	public function validate($data) {
		foreach ($this->rules as $rule) {
			if (!is_array($rule['rule'])) {
				$rule['rule'] = [$rule['rule']];
			}

			foreach($rule['rule'] as $callable) {
				// We only keep the first error for a specific key
				if (array_key_exists($rule['key'], $this->errors)) {
					break;
				}

				// If rule is optional and value doesn't exist, then everything is fine
				if ($rule['options']['optional'] && prop($rule['key'], $data) === null) {
					break;
				} else {
					// Validate
					if (!$callable(prop($rule['key'], $data), $data)) {
						$this->addError($rule['key'], $rule['message']);
					}
				}
			}
		}

		return (empty($this->errors));
	}

	public function getErrors($data = null) {
		if ($data) {
			$this->validate($data);
		}

		return $this->errors;
	}
}
