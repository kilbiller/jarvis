<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is null
 *
 * @return bool
 */
function isNull() {
	$isNull = function ($value) {
		return is_null($value);
	};

	return curry($isNull);
}
