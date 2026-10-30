<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

/**
 * Test if value length is between minimum and maximum (inclusive)
 *
 * @param int|null $min minimum, null to accept any value
 * @param int $max maximum
 * @return Closure(mixed): bool
 */
function lengthBetween(?int $min, int $max): Closure {
	return function (mixed $value) use ($min, $max): bool {
		// Validate successfully if min is null
		if ($min === null) {
			return true;
		}

		// A null value has a length of 0
		if ($value !== null && !is_scalar($value)) {
			return false;
		}

		$length = strlen((string) $value);
		return $min <= $length && $length <= $max;
	};
}
