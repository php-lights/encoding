<?php

namespace Neoncitylights\Encoding;

/**
 * @internal
 */
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

	/**
	 * A codepoint between U+D800 and U+DBFF, inclusive.
	 * @see https://infra.spec.whatwg.org/#leading-surrogate
	 */
	public static function isLeadingSurrogate( int $codePoint ): bool {
		return self::isWithin( $codePoint, 0xD800, 0xDBFF );
	}

	/**
	 * A codepoint between U+DC00 and 0xDFFF, inclusive.
	 * @see https://infra.spec.whatwg.org/#trailing-surrogate
	 */
	public static function isTrailingSurrogate( int $codePoint ): bool {
		return self::isWithin( $codePoint, 0xDC00, 0xDFFF );
	}

	/**
	 * A codepoint between U+0000 and U+007F, inclusive.
	 * @see https://infra.spec.whatwg.org/#ascii-code-point
	 */
	public static function isAscii( int $byte ): bool {
		return self::isWithin( $byte, 0x00, 0x7F );
	}

	public static function isWithin( int $n, int $min, int $max ): bool {
		return $n >= $min && $n <= $max;
	}
}
