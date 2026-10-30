<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value is a number between minimum and maximum (inclusive)
 *
 * @param int|float $min minimum
 * @param int|float $max maximum
 * @return Closure(mixed): bool
 */
function between(int|float $min, int|float $max): Closure {
	return fn (mixed $value): bool => is_numeric($value) && $min <= $value && $value <= $max;
}
