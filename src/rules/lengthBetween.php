<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is between minimum and maximum
 *
 * @param int|null $min minimum
 * @param int $max maximum
 * @return bool
 */
function lengthBetween($min, $max) {
	$lengthBetween = function ($min, $max, $value) {
		// Validate successfully if min is 0 or null
		if ($min === null) {
			return true;
		}

		$length = strlen($value);
		return $min <= $length && $length <= $max;
	};

	return curry($lengthBetween)($min, $max);
}
