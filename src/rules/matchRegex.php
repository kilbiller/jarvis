<?php

namespace Jarvis\rules;

use function fphp\curry;
use DateTime;

/**
 * Test if value match a regex
 *
 * @param string $regex Regex to validate against
 * @return bool
 */
function matchRegex($regex) {
	$matchRegex = function ($regex, $value) {
    	return preg_match($regex, $value) === 1;
	};

	return curry($matchRegex)($regex);
}
