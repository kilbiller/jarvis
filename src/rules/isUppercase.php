<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is a string in uppercase
 *
 * @return Closure(mixed): bool
 */
function isUppercase(): Closure {
	return fn (mixed $value): bool => is_string($value) && $value === strtoupper($value);
}
