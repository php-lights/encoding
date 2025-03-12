<?php

namespace Neoncitylights\Encoding;

class Utils {
	/**
	 * @see https://encoding.spec.whatwg.org/#scalar-value-from-surrogates
	 */
	public static function getScalarFromSurrogate(
		int $leadingSurrogate,
		int $trailingSurrogate,
	): int {
		return 0x10000
			+ ( ( $leadingSurrogate - 0xD800 ) << 10 )
			+ ( $trailingSurrogate - 0xDC00 );
	}

	public static function isInclusive( int $n, int $min, int $max ): bool {
		return $n >= $min && $n <= $max;
	}
}
