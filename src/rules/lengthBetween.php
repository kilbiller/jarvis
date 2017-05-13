<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is between minimum and maximum
 *
 * @param int $min minimum
 * @param int $max maximum
 * @return bool
 */
function lengthBetween($min, $max) {
	$lengthBetween = function ($min, $max, $value) {
		$length = strlen($value);
		return $min < $length && $length < $max;
	};

	return curry($lengthBetween)($min, $max);
}
