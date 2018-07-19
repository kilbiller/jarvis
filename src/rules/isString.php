<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is a string
 *
 * @return bool
 */
function isString() {
	$isString = function ($value) {
		return is_string($value);
	};

	return curry($isString);
}
