<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is positive
 *
 * @return bool
 */
function isPositive() {
	$isPositive = function ($value) {
		return isNumber($value) && $value > 0;
	};

	return curry($isPositive);
}
