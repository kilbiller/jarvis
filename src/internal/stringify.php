<?php

declare(strict_types=1);

namespace Jarvis\internal;

/**
 * String form of a value checked by a string rule, null when it has none
 *
 * Null is treated as an empty string and NaN is rejected since PHP 8.5 warns when casting it.
 *
 * @internal
 */
function stringify(mixed $value): ?string {
	return match (true) {
		$value === null => '',
		is_float($value) && is_nan($value) => null,
		is_scalar($value) => (string) $value,
		default => null,
	};
}
