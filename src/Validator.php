<?php

namespace Jarvis;

use Closure;
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

	function __construct($ruleset = null) {
		if ($ruleset instanceof RuleSet) {
			$this->__rules = $ruleset->getRules();
		}
	}

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

	public function validate($array) {
		foreach ($this->__rules as $rule) {
			$closure = Closure::fromCallable($rule['rule']);

			if (!array_key_exists($rule['key'], $this->__errors) && !$closure(prop($rule['key'], $array), $array)) {
				$this->__addError($rule['key'], $rule['message']);
			}
		}

		return (empty($this->__errors));
	}

	public function getErrors() {
		return $this->__errors;
	}
}