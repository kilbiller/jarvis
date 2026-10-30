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
	return fn (mixed $value): bool => $value === false || !empty($value);
}
