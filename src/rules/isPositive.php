<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is a strictly positive number
 *
 * @return Closure(mixed): bool
 */
function isPositive(): Closure {
	return fn (mixed $value): bool => is_numeric($value) && $value > 0;
}
