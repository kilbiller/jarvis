<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is null
 *
 * @return Closure(mixed): bool
 */
function isNull(): Closure {
	return fn (mixed $value): bool => $value === null;
}
