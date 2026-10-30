<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is a boolean
 *
 * @return Closure(mixed): bool
 */
function isBoolean(): Closure {
	return fn (mixed $value): bool => is_bool($value);
}
