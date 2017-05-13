<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is not empty
 *
 * @return bool
 */
function notEmpty() {
	$notEmpty = function ($value) {
		return !empty($value);
	};

	return curry($notEmpty);
}
