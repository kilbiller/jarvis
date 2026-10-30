<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

use function Jarvis\internal\stringify;

/**
 * Test if value is valid json
 *
 * @return Closure(mixed): bool
 */
function isJson(): Closure {
	return function (mixed $value): bool {
		$string = stringify($value);
		return $string !== null && json_validate($string);
	};
}
