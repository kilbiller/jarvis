<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is a number
 *
 * @return bool
 */
function isNumber() {
	$isNumber = function ($value) {
		return is_numeric($value);
	};

	return curry($isNumber);
}
