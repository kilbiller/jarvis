<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is a string
 *
 * @return Closure(mixed): bool
 */
function isString(): Closure {
	return fn (mixed $value): bool => is_string($value);
}
