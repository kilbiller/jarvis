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
function between($min, $max) {
	$between = function ($min, $max, $value) {
		return $min <= $value && $value <= $max;
	};

	return curry($between)($min, $max);
}
