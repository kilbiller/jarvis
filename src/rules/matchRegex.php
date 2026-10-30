<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value matches a regex
 *
 * @param string $regex Regex to validate against
 * @return Closure(mixed): bool
 */
function matchRegex(string $regex): Closure {
	return fn (mixed $value): bool => ($value === null || is_scalar($value))
		&& preg_match($regex, (string) $value) === 1;
}
