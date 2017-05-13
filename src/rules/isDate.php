<?php

namespace Jarvis\rules;

use function fphp\curry;
use DateTime;

/**
 * Test if value is a date
 *
 * @param string $format Date format to check against (default: 'Y-m-d')
 * @return bool
 */
function isDate($format = 'Y-m-d') {
	$isDate = function ($format, $value) {
		$d = DateTime::createFromFormat($format, $value);
    	return $d && $d->format($format) === $value;
	};

	return curry($isDate)($format);
}
