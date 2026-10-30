<?php

declare(strict_types=1);

namespace Jarvis\rules;

use Closure;

use function Jarvis\internal\stringify;

/**
 * Test if value matches a regex
 *
 * @param string $regex Regex to validate against
 * @return Closure(mixed): bool
 */
function matchRegex(string $regex): Closure {
	return function (mixed $value) use ($regex): bool {
		$string = stringify($value);
		return $string !== null && preg_match($regex, $string) === 1;
	};
}
