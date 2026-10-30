<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if at least one of the functions returns true
 *
 * @param callable ...$functions Validation functions receiving the value and the whole data
 * @return Closure(mixed, mixed=): bool
 */
function oneOf(callable ...$functions): Closure {
	return function (mixed $value, mixed $data = null) use ($functions): bool {
		foreach ($functions as $function) {
			if ($function($value, $data)) {
				return true;
			}
		}

		return false;
	};
}
