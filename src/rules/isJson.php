<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is valid json
 *
 * @return Closure(mixed): bool
 */
function isJson(): Closure {
	return fn (mixed $value): bool => is_scalar($value) && json_validate((string) $value);
}
