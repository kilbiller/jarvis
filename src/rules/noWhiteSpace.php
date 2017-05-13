<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value has no whitespace
 *
 * @return bool
 */
function noWhiteSpace() {
	$noWhiteSpace = function ($value) {
		return preg_match('/\s/', $value) === 0;
	};

	return curry($noWhiteSpace);
}
