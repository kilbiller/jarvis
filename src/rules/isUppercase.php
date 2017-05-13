<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is in uppercase
 *
 * @return bool
 */
function isUppercase() {
	$isUppercase = function ($value) {
		return $value === strtoupper($value);
	};

	return curry($isUppercase);
}
