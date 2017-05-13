<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is a boolean
 *
 * @return bool
 */
function isBoolean() {
	$isBoolean = function ($value) {
		return is_bool($value);
	};

	return curry($isBoolean);
}
