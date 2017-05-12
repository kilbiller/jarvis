<?php

namespace Jarvis;

use Exception;

class RuleSet {
	private $__rules = [];

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

	public function getRules() {
		return $this->__rules;
	}
}
