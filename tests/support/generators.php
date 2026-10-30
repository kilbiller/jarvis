<?php

declare(strict_types=1);

namespace Jarvis\Tests;

use ArrayAccess;
use ArrayObject;
use DateTimeImmutable;
use Random\Engine\Mt19937;
use Random\Randomizer;
use stdClass;

enum Suit: string {
	case Hearts = 'H';
	case Spades = 'S';
}

/**
 * Seed used by property tests, set JARVIS_SEED to replay a failure
 */
function seed(): int {
	$seed = getenv('JARVIS_SEED');
	return $seed === false ? random_int(0, 2 ** 31 - 1) : (int) $seed;
}

/**
 * Number of generated cases per property, set JARVIS_ITERATIONS to change it
 */
function iterations(): int {
	$iterations = getenv('JARVIS_ITERATIONS');
	return $iterations === false ? 200 : (int) $iterations;
}

function randomizer(int $seed): Randomizer {
	return new Randomizer(new Mt19937($seed));
}

/**
 * @template T
 * @param list<T> $values
 * @return T
 */
function pick(Randomizer $r, array $values): mixed {
	return $values[$r->pickArrayKeys($values, 1)[0]];
}

function anyInt(Randomizer $r): int {
	return $r->getInt(0, 1) === 0
		? pick($r, [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN])
		: $r->getInt(-1000, 1000);
}

function anyFloat(Randomizer $r): float {
	return $r->getInt(0, 1) === 0
		? pick($r, [0.0, -0.0, 0.5, -0.5, INF, -INF, NAN, PHP_FLOAT_EPSILON, 1e300])
		: $r->getFloat(-1000, 1000);
}

function anyString(Randomizer $r): string {
	return match ($r->getInt(0, 2)) {
		0 => pick($r, [
			'', ' ', "\t\n", 'abc', 'ABC', '123', '-5', '1e3', ' 12 ', '0x1A',
			'2012-02-24', '2012-02-30', '[]', '{"a":1}', '[1,2,]', 'null',
			'é', 'ÉCOLE', "\u{1F600}", "a\0b", "\xff\xfe",
		]),
		1 => $r->getBytesFromString("aB1 -.{}[]\"\n", $r->getInt(1, 12)),
		2 => $r->getBytes($r->getInt(1, 12)),
	};
}

function anyObject(Randomizer $r): object {
	return match ($r->getInt(0, 5)) {
		0 => new stdClass(),
		1 => new ArrayObject(['a' => 1]),
		2 => new DateTimeImmutable('2012-02-24'),
		3 => fn (): bool => true,
		4 => new class {
			public function __toString(): string {
				return 'stringable';
			}
		},
		5 => pick($r, Suit::cases()),
	};
}

/**
 * @return array<array-key, mixed>
 */
function anyArray(Randomizer $r, int $depth): array {
	$array = [];
	$isList = $r->getInt(0, 1) === 0;

	for ($i = $r->getInt(0, 4); $i > 0; $i--) {
		$value = anyValue($r, $depth - 1);
		if ($isList) {
			$array[] = $value;
		} else {
			$array[anyString($r)] = $value;
		}
	}

	return $array;
}

/**
 * Any value a validated payload could contain
 */
function anyValue(Randomizer $r, int $depth = 2): mixed {
	return match ($r->getInt(0, 7)) {
		0 => null,
		1 => $r->getInt(0, 1) === 1,
		2 => anyInt($r),
		3 => anyFloat($r),
		4, 5 => anyString($r),
		6 => $depth > 0 ? anyArray($r, $depth) : anyString($r),
		7 => anyObject($r),
	};
}

function show(mixed $value): string {
	if ($value === null || is_scalar($value)) {
		return var_export($value, true);
	}

	$json = json_encode($value, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
	return get_debug_type($value) . ' ' . $json;
}

/**
 * Build nested data where each key may be missing, with a random value otherwise
 *
 * @param list<string> $keys Dotted keys (i.e. 'user.firstname')
 */
function anyData(Randomizer $r, array $keys): array|ArrayAccess {
	$data = [];

	foreach ($keys as $key) {
		if ($r->getInt(0, 2) === 0) {
			continue;
		}

		$node = &$data;
		foreach (explode('.', $key) as $segment) {
			if ($node !== null && !is_array($node)) {
				// A parent key already holds a non array value
				continue 2;
			}
			$node = &$node[$segment];
		}
		$node = anyValue($r);
		unset($node);
	}

	return $r->getInt(0, 4) === 0 ? new ArrayObject($data) : $data;
}

/**
 * Value at a dotted key, null when missing
 */
function lookup(string $key, array|ArrayAccess $data): mixed {
	$value = $data;

	foreach (explode('.', $key) as $segment) {
		if ((!is_array($value) && !$value instanceof ArrayAccess) || !isset($value[$segment])) {
			return null;
		}
		$value = $value[$segment];
	}

	return $value;
}
