<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value has no whitespace
 *
 * @return Closure(mixed): bool
 */
function noWhiteSpace(): Closure {
	return fn (mixed $value): bool => ($value === null || is_scalar($value))
		&& preg_match('/\s/', (string) $value) === 0;
}
