<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is number-like
 *
 * @return Closure(mixed): bool
 */
function isNumber(): Closure {
	return fn (mixed $value): bool => is_numeric($value);
}
