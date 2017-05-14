<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if value is valid json
 *
 * @return bool
 */
function isJson() {
	$isJson = function ($value) {
		json_decode($value);
		return (json_last_error() == JSON_ERROR_NONE);
	};

	return curry($isJson);
}
