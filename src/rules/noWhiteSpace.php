<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

use function Jarvis\internal\stringify;

/**
 * Test if value has no whitespace
 *
 * @return Closure(mixed): bool
 */
function noWhiteSpace(): Closure {
	return function (mixed $value): bool {
		$string = stringify($value);
		return $string !== null && preg_match('/\s/', $string) === 0;
	};
}
