<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is not empty, false is considered not empty
 *
 * @return Closure(mixed): bool
 */
function notEmpty(): Closure {
	return fn (mixed $value): bool => match (true) {
		$value === false => true,
		// empty() warns on NAN since PHP 8.5, which is not empty
		is_float($value) => $value !== 0.0,
		default => !empty($value),
	};
}
