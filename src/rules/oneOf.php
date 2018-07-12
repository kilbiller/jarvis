<?php

namespace Jarvis\rules;

use function fphp\curry;

/**
 * Test if at least one of the conditions returns true 
 *
 * @param {...callable} $functions
 * @return bool
 */
function oneOf(...$functions) {
	$oneOf = function ($functions, $value, $data) {
		foreach ($functions as $function) {
			if ($function($value, $data)) {
				return true;
			}
		}

		return false;
	};

	return curry($oneOf)($functions);
}
