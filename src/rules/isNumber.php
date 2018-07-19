<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is number-like
 *
 * @return bool
 */
function isNumber() {
	$isNumber = function ($value) {
		return is_numeric($value);
	};

	return curry($isNumber);
}
