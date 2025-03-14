<?php

namespace Neoncitylights\Encoding\Tests;

use Neoncitylights\Encoding\Utils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass( Utils::class )]
class UtilsTest extends TestCase {
	#[DataProvider( "provideScalarFromSurrogates" )]
	public function testScalarFromSurrogates(
		int $scalar,
		int $leading,
		int $trailing,
	): void {
		$this->assertSame( $scalar, Utils::getScalarFromSurrogate( $leading, $trailing ) );
	}

	public static function provideScalarFromSurrogates(): array {
		return [
			[ 0x1F600, 0xD83D, 0xDE00 ], // 😀 (U+1F600)
			[ 0x1F602, 0xD83D, 0xDE02 ], // 😂 (U+1F602)
			[ 0x10437, 0xD801, 0xDC37 ], // 𐐷 (U+10437)
			[ 0x10FFFF, 0xDBFF, 0xDFFF ],
		];
	}

	#[DataProvider( 'provideIsLeadingSurrogate' )]
	public function testIsLeadingSurrogate( bool $expected, int $codePoint ): void {
		$this->assertSame( $expected, Utils::isLeadingSurrogate( $codePoint ) );
	}

	public static function provideIsLeadingSurrogate(): array {
		return [
			[ true, 0xD800 ],
			[ true, 0xDBFF ],
			[ true, 0xD8FF ],
			[ false, 0xD7FF ], // below bounds
			[ false, 0xDC00 ], // above bounds
		];
	}

	#[DataProvider( 'provideIsTrailingSurrogate' )]
	public function testIsTrailingSurrogate( bool $expected, int $codePoint ): void {
		$this->assertSame( $expected, Utils::isTrailingSurrogate( $codePoint ) );
	}

	public static function provideIsTrailingSurrogate(): array {
		return [
			[ true, 0xDC00 ],
			[ true, 0xDFFF ],
			[ true, 0xDCFF ],
			[ false, 0xDBFF ], // below bounds
			[ false, 0xE000 ], // above bounds
		];
	}

	#[DataProvider( 'provideIsAscii' )]
	public function testIsAscii( bool $expected, int $byte ): void {
		$this->assertSame( $expected, Utils::isAscii( $byte ) );
	}

	public static function provideIsAscii(): array {
		return [
			[ true, \ord( 'a' ) ],
			[ true, \ord( 'A' ) ],
			[ true, \ord( '0' ) ],
			[ true, \ord( '9' ) ],
			[ true, 0x007F ],
			[ false, 0x0080 ],
		];
	}

	#[DataProvider( 'provideIsWithin' )]
	public function testIsWithin( bool $expected, int $n, int $min, int $max ): void {
		$this->assertSame( $expected, Utils::isWithin( $n, $min, $max ) );
	}

	public static function provideIsWithin(): array {
		return [
			[ true, 0, -1, 1 ],
			[ false, 0, 2, 4 ],
		];
	}
}
