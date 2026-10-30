<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;
use DateTime;

/**
 * Test if value is a date string matching the given format
 *
 * @param string $format Date format to check against (default: 'Y-m-d')
 * @return Closure(mixed): bool
 */
function isDate(string $format = 'Y-m-d'): Closure {
	return function (mixed $value) use ($format): bool {
		// DateTime rejects null bytes with an exception
		if (!is_string($value) || str_contains($value, "\0")) {
			return false;
		}

		$date = DateTime::createFromFormat($format, $value);
		return $date !== false && $date->format($format) === $value;
	};
}
