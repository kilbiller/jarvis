<?php

namespace Jarvis;

use Exception;

function prop($key, $collection) {
	$keys = explode('.', $key);
	$result = $collection;

	foreach ($keys as $key) {
		if (isset($result[$key])) {
			$result = $result[$key];
		} else {
			return null;
		}
	}

	return $result;
}

class Validator {
	private $__rules = [];
	private $__errors = [];

	private function __addError($key, $message) {
		$message = str_replace ('${key}', $key, $message);
		$this->__errors[$key] = $message;
	}

	public function addRule($key, $rule, $message = '${key} is not valid.') {
		if (!is_string($key)) {
			throw new Exception('Param key should be a string.');
		}

		if (empty($key)) {
			throw new Exception('Param key should not be empty.');
		}

		if (!is_string($message)) {
			throw new Exception('Param message should be a string.');
		}

		$this->__rules[] = ['key' => $key, 'rule' => $rule, 'message' => $message];

		return $this;
	}

	public function validate($data) {
		foreach ($this->__rules as $rule) {
			if (!is_array($rule['rule'])) {
				$rule['rule'] = [$rule['rule']];
			}

			foreach($rule['rule'] as $callable) {
				if (!array_key_exists($rule['key'], $this->__errors)) {
					if (!$callable(prop($rule['key'], $data), $data)) {
						$this->__addError($rule['key'], $rule['message']);
					}
				} else {
					break;
				}
			}
		}

		return (empty($this->__errors));
	}

	public function getErrors($data = null) {
		if ($data) {
			$this->validate($data);
		}

		return $this->__errors;
	}
}
